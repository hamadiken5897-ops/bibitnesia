<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class StatusPesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::whereIn('status_pesanan', [
            'Menunggu Kurir',
            'Pesanan dalam pengiriman',
            'Pesanan Selesai'
        ])
            ->with(['user', 'pengiriman'])
            ->orderByRaw("FIELD(status_pesanan,
                'Menunggu Kurir',
                'Pesanan dalam pengiriman',
                'Pesanan Selesai'
                 )
            ")
            ->orderBy('created_at', 'asc')
            ->get();



        return view('penjual.status-pesanan.index', compact('pesanan'));
    }
}
