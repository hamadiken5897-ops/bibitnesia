<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class StatusPesananController extends Controller
{
    public function index()
    {
        $penjual = auth()->user()->penjual;
        if (!$penjual) {
            abort(403, 'Akun ini bukan penjual');
        }

        $pesanan = Pesanan::whereIn('status_pesanan', [
            'Pesanan Selesai',
            'Pesanan ditolak'
        ])
            ->whereHas('detailPesanan.produk', function ($query) use ($penjual) {
                $query->where('id_penjual', $penjual->id_penjual);
            })
            ->with(['user', 'pengiriman.kurir.user'])
            ->orderBy('updated_at', 'desc')
            ->get();



        return view('penjual.status-pesanan.index', compact('pesanan'));
    }
}
