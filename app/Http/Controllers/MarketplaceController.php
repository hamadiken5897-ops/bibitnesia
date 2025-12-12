<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\NotifikasiUser;
class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::with('user');

        // Filter berdasarkan search
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_produk', 'like', '%' . $request->search . '%')->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // Filter berdasarkan harga
        if ($request->has('harga_min') && $request->harga_min != '') {
            $query->where('harga', '>=', $request->harga_min);
        }
        if ($request->has('harga_max') && $request->harga_max != '') {
            $query->where('harga', '<=', $request->harga_max);
        }

        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'termurah':
                    $query->orderBy('harga', 'asc');
                    break;
                case 'termahal':
                    $query->orderBy('harga', 'desc');
                    break;
                case 'terbaru':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $produk = $query->where('status', 'aktif')->paginate(12);

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
        $produk = Produk::with('user')->where('kategori', $kategori)->where('status', 'aktif')->orderBy('created_at', 'desc')->paginate(12);

        // ========== Notifikasi ==========
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
        $produk = Produk::with('user')->findOrFail($id);

        $produkTerkait = Produk::where('kategori', $produk->kategori)->where('id_produk', '!=', $id)->where('status', 'aktif')->limit(4)->get();

        // ========== Notifikasi ==========
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

        return view('marketplace.detail', compact('produk', 'produkTerkait', 'notifCount', 'notifLatest'));
    }
}
