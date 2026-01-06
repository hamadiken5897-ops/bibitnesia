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
        $produk = Produk::where('id_produk', $request->id_produk)->firstOrFail();
        $jumlah = max(1, (int) $request->jumlah);

        $items = [
            [
                'id_produk' => $produk->id_produk,
                'nama' => $produk->nama_produk,
                'harga' => $produk->harga,
                'jumlah' => $jumlah,
                'subtotal' => $produk->harga * $jumlah,
            ],
        ];

        $totalProduk = collect($items)->sum('subtotal');

        $provinsi = Provinsi::orderBy('nama_provinsi')->get();

        return view('marketplace.checkout', [
            'items' => $items,
            'totalProduk' => $totalProduk,
            'provinsi' => $provinsi,
            'ongkirTetap' => self::ONGKIR_TETAP, // 🔥 INI WAJIB
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
            $produk = Produk::where('id_produk', $item['id_produk'])->firstOrFail();
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
                'provinsi' => $request->provinsi,

                'status_pesanan' => 'Menunggu Pembayaran',
                'created_at' => now(),
                'updated_at' => now(),
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
            // 4️⃣ GENERATE VA
            // ===============================
            $vaNomor = '8801' . str_pad(preg_replace('/\D/', '', $pesananId), 8, '0', STR_PAD_LEFT);

            // ===============================
            // 5️⃣ INSERT PEMBAYARAN (PENDING)
            // ===============================
            DB::table('pembayarans')->insert([
                'id_pembayaran' => 'PAY-' . strtoupper(Str::random(10)),
                'id_pesanan' => $pesananId,
                'metode_pembayaran' => 'VA BANK',
                'va_bank' => 'BCA',
                'va_nomor' => $vaNomor,
                'total_bayar' => $totalHarga,
                'status_validasi' => 'pending',
                'expired_at' => now()->addHours(24),
                'tanggal_pembayaran' => null,
                'bukti_pembayaran' => null,
                'tgl_validasi' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        // ===============================
        // 6️⃣ REDIRECT KE INVOICE
        // ===============================
        return redirect()->route('marketplace.invoice', $pesananId);
    }
}
