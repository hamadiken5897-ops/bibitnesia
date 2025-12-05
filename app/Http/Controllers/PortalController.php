<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index()
    {
        // Ambil 8 produk populer (misalnya berdasarkan views atau rating)
        $produkPopuler = Produk::with(['user', 'kategori'])
            ->where('status', 'tersedia')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('portal', compact('produkPopuler'));
    }
}