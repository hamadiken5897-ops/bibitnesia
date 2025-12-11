<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;

class PesananController extends Controller
{
    public function index()
    {
        // Ambil semua pesanan urut terbaru
        $pesanan = Pesanan::orderBy('created_at', 'DESC')->get();

        // Kirim ke view
        return view('penjual.pesanan', compact('pesanan'));
    }
}
