<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Produk;
use App\Models\Provinsi;

class CheckoutController extends Controller
{
    /**
     * TAMPIL HALAMAN CHECKOUT
     *
     */

    private const ONGKIR_TETAP = 15000;

    public function create(Request $request)
    {
        $items = [];
        $isFromCart = false;

        if ($request->has('from_cart') && $request->from_cart == '1') {
            $keranjang = \App\Models\Keranjang::where('user_id', auth()->user()->id_user)->with('produk')->get();
            if ($keranjang->isEmpty()) {
                return redirect()->route('keranjang.index')->with('error', 'Keranjang Anda kosong.');
            }
            foreach ($keranjang as $k) {
                $items[] = [
                    'id_produk' => $k->produk->id_produk,
                    'nama' => $k->produk->nama_produk,
                    'harga' => $k->produk->harga,
                    'jumlah' => $k->qty,
                    'subtotal' => $k->produk->harga * $k->qty,
                ];
            }
            $isFromCart = true;
        } else {
            $produk = Produk::where('id_produk', $request->id_produk)->with('penjual')->firstOrFail();
            
            if ($produk->penjual && $produk->penjual->id_user == auth()->user()->id_user) {
                return redirect()->back()->with('error', 'Anda tidak bisa membeli produk Anda sendiri!');
            }

            $jumlah = max(1, (int) $request->jumlah);

            $items[] = [
                'id_produk' => $produk->id_produk,
                'nama' => $produk->nama_produk,
                'harga' => $produk->harga,
                'jumlah' => $jumlah,
                'subtotal' => $produk->harga * $jumlah,
            ];
        }

        $totalProduk = collect($items)->sum('subtotal');

        $provinsi = Provinsi::orderBy('nama_provinsi')->get();
        
        $alamats = auth()->user()->alamats;
        $alamatUtama = $alamats->where('is_utama', true)->first() ?? $alamats->first();

        return view('marketplace.checkout', [
            'items' => $items,
            'totalProduk' => $totalProduk,
            'provinsi' => $provinsi,
            'alamats' => $alamats,
            'alamatUtama' => $alamatUtama,
            'ongkirTetap' => self::ONGKIR_TETAP, // 🔥 INI WAJIB
            'is_from_cart' => $isFromCart ? 1 : 0,
        ]);
    }

    /**
     * SIMPAN PESANAN (MULTI PRODUK)
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
            $produk = Produk::where('id_produk', $item['id_produk'])->with('penjual')->firstOrFail();
            
            if ($produk->penjual && $produk->penjual->id_user == auth()->user()->id_user) {
                return redirect()->back()->with('error', 'Anda tidak bisa membeli produk Anda sendiri!');
            }

            $totalProduk += $produk->harga * $item['jumlah'];
        }

        $totalHarga = $totalProduk + $request->ongkir;

        $pesananId = null;

        DB::transaction(function () use ($request, $totalHarga, &$pesananId) {
            // ===============================
            // 1️⃣ GENERATE ID & INVOICE
            // ===============================
            $pesananId = 'ORD-' . strtoupper(Str::random(10));

            $today = now()->format('Ymd');

            $lastInvoice = DB::table('pesanans')->whereDate('created_at', today())->orderBy('created_at', 'desc')->value('kode_invoice');

            $urutan = $lastInvoice ? str_pad((int) substr($lastInvoice, -6) + 1, 6, '0', STR_PAD_LEFT) : '000001';

            $kodeInvoice = "INV-{$today}-{$urutan}";

            // ===============================
            // 2️⃣ INSERT PESANAN
            // ===============================
            DB::table('pesanans')->insert([
                'id_pesanan' => $pesananId,
                'kode_invoice' => $kodeInvoice,
                'id_user' => auth()->user()->id_user,
                'tanggal_pesanan' => now(),
                'total_harga' => $totalHarga,

                // 🔥 SNAPSHOT DARI CHECKOUT
                'alamat' => $request->alamat,
                'catatan' => $request->catatan ?? null,
                'provinsi' => $request->provinsi,
                'status_pesanan' => 'Menunggu Pembayaran',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // CATAT RIWAYAT
            \App\Models\RiwayatPesanan::create([
                'id_pesanan' => $pesananId,
                'status' => 'Pesanan Dibuat',
                'deskripsi' => 'Pesanan berhasil dibuat dan menunggu pembayaran.'
            ]);

            // ===============================
            // 3️⃣ DETAIL PESANAN
            // ===============================
            foreach ($request->items as $item) {
                $produk = Produk::where('id_produk', $item['id_produk'])->firstOrFail();

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

            // ===============================
            // 4. GENERATE VA / MIDTRANS TOKEN
            // ===============================
            $vaNomor = '8801' . str_pad(preg_replace('/\D/', '', $pesananId), 8, '0', STR_PAD_LEFT);
            $snapToken = null;

            // Cek Pengaturan Midtrans
            $pengaturan = \App\Models\PengaturanPembayaran::first();
            if ($pengaturan && $pengaturan->midtrans_is_active && $pengaturan->midtrans_server_key) {
                \Midtrans\Config::$serverKey = $pengaturan->midtrans_server_key;
                \Midtrans\Config::$isProduction = false; // Gunakan Sandbox
                \Midtrans\Config::$isSanitized = true;
                \Midtrans\Config::$is3ds = true;

                $user = auth()->user();

                $params = [
                    'transaction_details' => [
                        'order_id' => $pesananId,
                        'gross_amount' => $totalHarga,
                    ],
                    'customer_details' => [
                        'first_name' => $user->nama_lengkap,
                        'email' => $user->email,
                        'phone' => $user->no_hp ?? '',
                    ],
                ];

                if ($request->metode !== 'other_qris') {
                    $params['enabled_payments'] = [$request->metode];
                } else {
                    $params['enabled_payments'] = ['other_qris'];
                }

                try {
                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                } catch (\Exception $e) {
                    // Jika gagal, biarkan snapToken null, sistem akan fallback ke pembayaran manual
                    \Log::error('Midtrans Snap Error: ' . $e->getMessage());
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

            // ===============================
            // 5. INSERT PEMBAYARAN (PENDING)
            // ===============================
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

        if ($request->has('is_from_cart') && $request->is_from_cart == '1') {
            \App\Models\Keranjang::where('user_id', auth()->user()->id_user)->delete();
        }

        // ===============================
        // 6️⃣ REDIRECT KE INVOICE
        // ===============================
        return redirect()->route('marketplace.invoice', $pesananId);
    }
}
