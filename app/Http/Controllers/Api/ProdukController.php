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
        // Mengambil semua produk dengan relasi penjual (opsional, jika butuh nama toko)
        $produks = Produk::with('penjual:id_penjual,nama_toko')->get();

        // Mapping data agar url foto bisa diakses langsung dari Flutter
        $produks->transform(function ($produk) {
            $produk->foto_produk1_url = $produk->foto_produk1 ? asset('storage/' . $produk->foto_produk1) : null;
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
        $produk = Produk::with('penjual:id_penjual,nama_toko')->find($id);

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
}
