<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;

class RiwayatController extends Controller
{
    public function riwayat()
    {
        $riwayat = Pesanan::where('id_user', auth()->user()->id_user)
            ->where('status_pesanan', 'Pesanan Selesai')
            ->with(['detailPesanan.produk'])
            ->latest()
            ->get();

        return view('marketplace.riwayat', compact('riwayat'));
    }
}
