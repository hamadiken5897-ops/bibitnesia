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
     */
    public function create(Request $request)
    {
        $produk = Produk::where('id_produk', $request->id_produk)->firstOrFail();
        $jumlah = max(1, (int) $request->jumlah);

        $items = [
            [
                'id_produk' => $produk->id_produk,
                'nama'      => $produk->nama_produk,
                'harga'     => $produk->harga,
                'jumlah'    => $jumlah,
                'subtotal'  => $produk->harga * $jumlah,
            ],
        ];

        $totalProduk = collect($items)->sum('subtotal');

        $provinsi = Provinsi::orderBy('nama_provinsi')->get();

        return view('marketplace.checkout', compact(
            'items',
            'totalProduk',
            'provinsi'
        ));
    }

    /**
     * SIMPAN PESANAN (MULTI PRODUK)
     */
    public function store(Request $request)
    {
        $request->validate([
            'items'                 => 'required|array|min:1',
            'items.*.id_produk'     => 'required',
            'items.*.jumlah'        => 'required|integer|min:1',
            'provinsi'              => 'required',
            'ongkir'                => 'required|integer|min:0',
            'metode'                => 'required',
            'alamat'                => 'required|string',
        ]);

        $totalProduk = 0;

        // 🔐 HITUNG TOTAL PRODUK DI SERVER
        foreach ($request->items as $item) {
            $produk = Produk::where('id_produk', $item['id_produk'])->firstOrFail();
            $totalProduk += $produk->harga * $item['jumlah'];
        }

        $totalHarga = $totalProduk + $request->ongkir;

        DB::transaction(function () use ($request, $totalHarga) {

            $idPesanan = 'ORD-' . strtoupper(Str::random(10));

            // 1️⃣ PESANAN
            DB::table('pesanans')->insert([
                'id_pesanan'      => $idPesanan,
                'id_user'         => auth()->user()->id_user,
                'tanggal_pesanan' => now(),
                'total_harga'     => $totalHarga,
                'status_pesanan'  => 'Menunggu Pembayaran',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // 2️⃣ DETAIL PESANAN (MULTI PRODUK)
            foreach ($request->items as $item) {

                $produk = Produk::where('id_produk', $item['id_produk'])->firstOrFail();

                DB::table('detail_pesanans')->insert([
                    'id_detail_pesanan' => 'DTL-' . strtoupper(Str::random(10)),
                    'id_pesanan'        => $idPesanan,
                    'id_produk'         => $produk->id_produk,
                    'harga_satuan'      => $produk->harga,
                    'jumlah'            => $item['jumlah'],
                    'ongkir'            => $request->ongkir,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }

            // 3️⃣ PEMBAYARAN
            DB::table('pembayarans')->insert([
                'id_pembayaran'      => 'PAY-' . strtoupper(Str::random(10)),
                'id_pesanan'         => $idPesanan,
                'metode_pembayaran'  => $request->metode,
                'total_bayar'        => $totalHarga,
                'tanggal_pembayaran' => null,
                'status_validasi'    => 'Pending',
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        });

        return redirect()
            ->route('marketplace.index')
            ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
    }
}
