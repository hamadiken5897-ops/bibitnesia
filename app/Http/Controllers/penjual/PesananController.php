<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = \App\Models\Pesanan::where('status_pesanan', 'Pesanan sedang diproses')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('penjual.pesanan', compact('pesanan'));
    }
}
