<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\NotifikasiUser;

class PortalController extends Controller
{
    public function index()
    {
        // Ambil produk populer
        $produkPopuler = Produk::with(['user', 'kategori'])
            ->where('status', 'tersedia')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // ==== Notifikasi hanya jika user login ====
        $notifCount = 0;
        $notifLatest = [];

        if (auth()->check()) {
            $notifCount = NotifikasiUser::where('id_user', auth()->user()->id_user)
                ->where('is_read', 0)
                ->count();

            $notifLatest = NotifikasiUser::where('id_user', auth()->user()->id_user)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('portal', compact('produkPopuler', 'notifCount', 'notifLatest'));
    }
}
