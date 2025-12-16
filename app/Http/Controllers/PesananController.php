<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = DB::table('detail_pesanans')
            ->join('pesanans', 'detail_pesanans.id_pesanan', '=', 'pesanans.id_pesanan')
            ->join('produks', 'detail_pesanans.id_produk', '=', 'produks.id_produk')
            ->where('pesanans.id_user', auth()->user()->id_user)
            ->select(
                'pesanans.id_pesanan',
                'pesanans.status_pesanan',
                'pesanans.total_harga',
                'pesanans.created_at',

                'detail_pesanans.jumlah',

                'produks.nama_produk',
                'produks.foto_produk1',
            )
            ->orderBy('pesanans.created_at', 'desc')
            ->get();

        return view('marketplace.pesanan', compact('pesanan'));
    }
    public function selesai($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status !== 'selesai') {
            // catat pemasukan penjual
            LaporanPenjual::firstOrCreate(
                ['id_pesanan' => $pesanan->id_pesanan],
                [
                    'id_penjual' => $pesanan->id_penjual,
                    'jumlah' => $pesanan->total_harga,
                ],
            );

            $pesanan->update(['status' => 'selesai']);
        }

        return back()->with('success', 'Pesanan selesai & pemasukan dicatat');
    }
}
