<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::where('id_user', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('marketplace.pesanan', [
            'pesanan' => $pesanan
        ]);
    }
}
