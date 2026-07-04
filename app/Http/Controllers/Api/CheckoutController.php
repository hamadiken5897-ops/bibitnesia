<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Produk;

class CheckoutController extends Controller
{
    /**
     * SIMPAN PESANAN VIA API MOBILE
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id_produk' => 'required',
            'items.*.jumlah' => 'required|integer|min:1',
            'provinsi' => 'required',
            'ongkir' => 'required|integer|min:0',
            'metode' => 'required',
            'alamat' => 'required|string',
        ]);

        $totalProduk = 0;

        foreach ($request->items as $item) {
            $produk = Produk::where('id_produk', $item['id_produk'])->with('penjual')->first();
            
            if (!$produk) {
                return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.'], 404);
            }

            if ($produk->penjual && $produk->penjual->id_user == auth()->user()->id_user) {
                return response()->json(['success' => false, 'message' => 'Anda tidak bisa membeli produk Anda sendiri!'], 400);
            }

            $totalProduk += $produk->harga * $item['jumlah'];
        }

        $totalHarga = $totalProduk + $request->ongkir;
        $pesananId = null;
        $snapToken = null;

        try {
            DB::transaction(function () use ($request, $totalHarga, &$pesananId, &$snapToken) {
                // 1. GENERATE ID & INVOICE
                $pesananId = 'ORD-' . strtoupper(Str::random(10));
                $today = now()->format('Ymd');
                $lastInvoice = DB::table('pesanans')->whereDate('created_at', today())->orderBy('created_at', 'desc')->value('kode_invoice');
                $urutan = $lastInvoice ? str_pad((int) substr($lastInvoice, -6) + 1, 6, '0', STR_PAD_LEFT) : '000001';
                $kodeInvoice = "INV-{$today}-{$urutan}";

                // 2. INSERT PESANAN
                DB::table('pesanans')->insert([
                    'id_pesanan' => $pesananId,
                    'kode_invoice' => $kodeInvoice,
                    'id_user' => auth()->user()->id_user,
                    'tanggal_pesanan' => now(),
                    'total_harga' => $totalHarga,
                    'alamat' => $request->alamat,
                    'catatan' => $request->catatan ?? null,
                    'provinsi' => $request->provinsi,
                    'status_pesanan' => 'Menunggu Pembayaran',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                \App\Models\RiwayatPesanan::create([
                    'id_pesanan' => $pesananId,
                    'status' => 'Pesanan Dibuat',
                    'deskripsi' => 'Pesanan berhasil dibuat dan menunggu pembayaran via Mobile.'
                ]);

                // 3. DETAIL PESANAN
                foreach ($request->items as $item) {
                    $produk = Produk::where('id_produk', $item['id_produk'])->first();
                    DB::table('detail_pesanans')->insert([
                        'id_detail_pesanan' => 'DTL-' . strtoupper(Str::random(10)),
                        'id_pesanan' => $pesananId,
                        'id_produk' => $produk->id_produk,
                        'harga_satuan' => $produk->harga,
                        'jumlah' => $item['jumlah'],
                        'ongkir' => $request->ongkir,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // 4. GENERATE MIDTRANS TOKEN
                $vaNomor = '8801' . str_pad(preg_replace('/\D/', '', $pesananId), 8, '0', STR_PAD_LEFT);
                
                $pengaturan = \App\Models\PengaturanPembayaran::first();
                if ($pengaturan && $pengaturan->midtrans_is_active && $pengaturan->midtrans_server_key) {
                    \Midtrans\Config::$serverKey = $pengaturan->midtrans_server_key;
                    \Midtrans\Config::$isProduction = false;
                    \Midtrans\Config::$isSanitized = true;
                    \Midtrans\Config::$is3ds = true;

                    $user = auth()->user();

                    $params = [
                        'transaction_details' => [
                            'order_id' => $pesananId,
                            'gross_amount' => $totalHarga,
                        ],
                        'customer_details' => [
                            'first_name' => $user->nama_lengkap ?? $user->name ?? 'Mobile User',
                            'email' => $user->email ?? 'mobile@user.com',
                            'phone' => $user->no_hp ?? '08000000',
                        ],
                    ];

                    try {
                        $snapToken = \Midtrans\Snap::getSnapToken($params);
                    } catch (\Exception $e) {
                        \Log::error('Midtrans Snap Error API: ' . $e->getMessage());
                    }
                }

                // Map metode untuk database ENUM
                $metodeEnum = 'QRIS';
                $vaBankStr = null;
                if (str_contains($request->metode, '_va')) {
                    $metodeEnum = 'VA BANK';
                    $vaBankStr = strtoupper(str_replace('_va', '', $request->metode));
                } else if (in_array($request->metode, ['gopay', 'shopeepay'])) {
                    $metodeEnum = 'E-Wallet';
                }

                // 5. INSERT PEMBAYARAN (PENDING)
                DB::table('pembayarans')->insert([
                    'id_pembayaran' => 'PAY-' . strtoupper(Str::random(10)),
                    'id_pesanan' => $pesananId,
                    'metode_pembayaran' => $metodeEnum,
                    'va_bank' => $vaBankStr,
                    'va_nomor' => $metodeEnum == 'VA BANK' ? $vaNomor : null,
                    'total_bayar' => $totalHarga,
                    'status_validasi' => 'pending',
                    'expired_at' => now()->addHours(24),
                    'tanggal_pembayaran' => null,
                    'bukti_pembayaran' => null,
                    'tgl_validasi' => null,
                    'snap_token' => $snapToken,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

            // Jika dipesan dari keranjang, hapus keranjangnya
            if ($request->has('is_from_cart') && $request->is_from_cart == '1') {
                \App\Models\Keranjang::where('user_id', auth()->user()->id_user)->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat.',
                'data' => [
                    'pesanan_id' => $pesananId,
                    'snap_token' => $snapToken,
                    'total_harga' => $totalHarga,
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Checkout API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pesanan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkPayment(Request $request, $id)
    {
        $pesanan = \App\Models\Pesanan::where('id_pesanan', $id)->where('id_user', auth()->id())->first();
        if (!$pesanan) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
        }

        if ($pesanan->status_pesanan != 'Menunggu Pembayaran') {
            return response()->json(['success' => true, 'status' => $pesanan->status_pesanan]);
        }

        $pengaturan = \App\Models\PengaturanPembayaran::first();
        if ($pengaturan && $pengaturan->midtrans_is_active && $pengaturan->midtrans_server_key) {
            \Midtrans\Config::$serverKey = $pengaturan->midtrans_server_key;
            \Midtrans\Config::$isProduction = false;
            
            try {
                $status = \Midtrans\Transaction::status($id);
                $transactionStatus = $status->transaction_status;
                
                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                    $pesanan->update(['status_pesanan' => 'Menunggu Konfirmasi']);
                    \App\Models\Pembayaran::where('id_pesanan', $id)->update(['status_pembayaran' => 'success']);
                    
                    \App\Models\RiwayatPesanan::create([
                        'id_pesanan' => $id,
                        'status' => 'Menunggu Konfirmasi',
                        'keterangan' => 'Pembayaran berhasil dikonfirmasi. Pesanan sedang diproses.',
                    ]);
                    
                    return response()->json(['success' => true, 'status' => 'Menunggu Konfirmasi']);
                }
                
                return response()->json(['success' => true, 'status' => 'Menunggu Pembayaran']);
            } catch (\Exception $e) {
                // Not found in Midtrans or error
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        
        return response()->json(['success' => true, 'status' => $pesanan->status_pesanan]);
    }
}
