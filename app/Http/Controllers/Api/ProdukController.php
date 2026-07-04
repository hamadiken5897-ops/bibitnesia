<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Menampilkan daftar semua produk (bibit)
     */
    public function index()
    {
        // Mengambil semua produk yang stoknya > 0 dan berstatus tersedia
        $query = Produk::with(['penjual:id_penjual,nama_penjual,id_provinsi,id_user', 'penjual.provinsi', 'penjual.user:id_user,profile_image', 'ulasans', 'ulasans.user'])
            ->withCount('ulasans as ulasan_count')
            ->withAvg('ulasans as rating', 'rating')
            ->where('stok', '>', 0)
            ->where('status', 'tersedia');
            
        if (request()->has('seller_id')) {
            $query->where('id_penjual', request('seller_id'));
        }

        // Filter berdasarkan pencarian (nama_produk)
        if (request()->filled('search')) {
            $query->where('nama_produk', 'like', '%' . request('search') . '%');
        }

        // Filter berdasarkan lokasi (provinsi)
        if (request()->filled('lokasi')) {
            $query->whereHas('penjual.provinsi', function ($q) {
                $q->where('nama_provinsi', 'like', '%' . request('lokasi') . '%');
            });
        }
            
        $produks = $query->get();

        // Mapping data agar url foto bisa diakses langsung dari Flutter
        $produks->transform(function ($produk) {
            $produk->foto_produk1_url = $produk->foto_produk1 ? asset('storage/' . $produk->foto_produk1) : null;
            $produk->foto_produk2_url = $produk->foto_produk2 ? asset('storage/' . $produk->foto_produk2) : null;
            $produk->foto_produk3_url = $produk->foto_produk3 ? asset('storage/' . $produk->foto_produk3) : null;
            return $produk;
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar produk berhasil diambil',
            'data'    => $produks
        ], 200);
    }

    /**
     * Menampilkan detail satu produk
     */
    public function show($id)
    {
        $produk = Produk::with(['penjual:id_penjual,nama_penjual,id_provinsi,id_user', 'penjual.provinsi', 'penjual.user:id_user,profile_image', 'ulasans', 'ulasans.user'])
            ->withCount('ulasans as ulasan_count')
            ->withAvg('ulasans as rating', 'rating')
            ->find($id);

        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
                'data'    => null
            ], 404);
        }

        $produk->foto_produk1_url = $produk->foto_produk1 ? asset('storage/' . $produk->foto_produk1) : null;
        $produk->foto_produk2_url = $produk->foto_produk2 ? asset('storage/' . $produk->foto_produk2) : null;
        $produk->foto_produk3_url = $produk->foto_produk3 ? asset('storage/' . $produk->foto_produk3) : null;

        return response()->json([
            'success' => true,
            'message' => 'Detail produk berhasil diambil',
            'data'    => $produk
        ], 200);
    }

    /**
     * Menampilkan daftar lokasi provinsi yang tersedia
     */
    public function getLokasi()
    {
        $lokasi = \App\Models\Provinsi::select('id_provinsi', 'nama_provinsi')
            ->whereHas('penjuals.produks', function($q) {
                $q->where('stok', '>', 0)->where('status', 'tersedia');
            })->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar lokasi berhasil diambil',
            'data'    => $lokasi
        ], 200);
    }
}
