<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\LaporanPenjual;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::with(['detailPesanan.produk'])
            ->where('id_user', auth()->user()->id_user)
            ->where('status_pesanan', '!=', 'Pesanan Selesai')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('marketplace.pesanan', compact('pesanan'));
    }
    public function selesai($id)
    {
        $pesanan = Pesanan::with('detailPesanan.produk')->findOrFail($id);

        if ($pesanan->status_pesanan !== 'Pesanan selesai' && $pesanan->status_pesanan !== 'Pesanan Selesai') {
            
            DB::transaction(function () use ($pesanan) {
                // Cek agar tidak double entry jika kurir sudah menyelesaikannya
                $sudahDicatat = LaporanPenjual::where('id_pesanan', $pesanan->id_pesanan)->exists();
                
                if (!$sudahDicatat) {
                    // Loop melalui detail pesanan untuk membagi pendapatan ke masing-masing penjual
                    foreach ($pesanan->detailPesanan as $detail) {
                        if ($detail->produk && $detail->produk->id_penjual) {
                            $pendapatan = $detail->harga_satuan * $detail->jumlah;
                            
                            // catat pemasukan per penjual
                            LaporanPenjual::create([
                                'id_penjual' => $detail->produk->id_penjual,
                                'id_pesanan' => $pesanan->id_pesanan,
                                'jumlah' => $pendapatan,
                            ]);
                        }
                    }
                }

                $pesanan->update(['status_pesanan' => 'Pesanan Selesai']);
            });
        }

        return back()->with('success', 'Pesanan selesai & pemasukan dicatat');
    }
}
