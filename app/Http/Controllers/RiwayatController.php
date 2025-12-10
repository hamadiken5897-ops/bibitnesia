<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;

class RiwayatController extends Controller
{
    public function riwayat()
    {
        // ambil semua pesanan milik user tanpa filter status
        $riwayat = Pesanan::where('id_user', auth()->user()->id_user)
            ->latest()
            ->get();

        return view('marketplace.riwayat', compact('riwayat'));
    }
}
