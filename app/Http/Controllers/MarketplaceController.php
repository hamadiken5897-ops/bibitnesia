<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\NotifikasiUser;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with(['penjual', 'penjual.provinsi']);

        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', '%' . $search . '%')->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter berdasarkan harga minimum
        if ($request->filled('harga_min')) {
            $query->where('harga', '>=', $request->harga_min);
        }

        // Filter berdasarkan harga maximum
        if ($request->filled('harga_max')) {
            $query->where('harga', '<=', $request->harga_max);
        }

        // Filter berdasarkan lokasi (provinsi)
        if ($request->filled('lokasi')) {
            $query->whereHas('penjual.provinsi', function ($q) use ($request) {
                $q->where('nama_provinsi', 'like', '%' . $request->lokasi . '%');
            });
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'termurah':
                    $query->orderBy('harga', 'asc');
                    break;
                case 'termahal':
                    $query->orderBy('harga', 'desc');
                    break;
                case 'terbaru':
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Filter produk tersedia saja
        $produk = $query->where('status', 'tersedia')->paginate(12);

        // Notifikasi
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

        return view('marketplace.index', compact('produk', 'notifCount', 'notifLatest'));
    }

    public function kategori($kategori)
    {
        $produk = Produk::with('penjual')->where('kategori', $kategori)->where('status', 'tersedia')->orderBy('created_at', 'desc')->paginate(12);

        // Notifikasi
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

        return view('marketplace.index', compact('produk', 'kategori', 'notifCount', 'notifLatest'));
    }

    public function show($id)
    {
        $produk = Produk::with(['penjual', 'penjual.provinsi', 'penjual.user'])
            ->where('id_produk', $id)
            ->where('status', 'tersedia')
            ->firstOrFail();

        $produkTerkait = Produk::with('penjual')->where('kategori', $produk->kategori)->where('id_produk', '!=', $id)->where('status', 'tersedia')->limit(4)->get();

        // Notifikasi
        $notifCount = 0;
        $notifLatest = [];
        $isFavorit = false;

        if (auth()->check()) {
            $notifCount = NotifikasiUser::where('id_user', auth()->user()->id_user)
                ->where('is_read', 0)
                ->count();

            $notifLatest = NotifikasiUser::where('id_user', auth()->user()->id_user)
                ->latest()
                ->take(5)
                ->get();
                
            $isFavorit = \App\Models\Favorit::where('id_user', auth()->user()->id_user)
                ->where('produk_id', $id)
                ->exists();
        }

        return view('marketplace.detail', compact('produk', 'produkTerkait', 'notifCount', 'notifLatest', 'isFavorit'));
    }
}
