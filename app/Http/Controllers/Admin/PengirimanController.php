<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengiriman;

class PengirimanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengiriman::with(['pesanan.detailPesanan.produk.penjual.user', 'pesanan.user', 'kurir.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_resi', 'like', "%{$search}%")
                  ->orWhere('id_pengiriman', 'like', "%{$search}%")
                  ->orWhereHas('pesanan.user', function($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%");
                  })
                  ->orWhereHas('pesanan.detailPesanan.produk.penjual.user', function($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status_pengiriman', $request->status);
        }

        $pengiriman = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.services.pengiriman', compact('pengiriman'));
    }

    public function show($id)
    {
        $pengiriman = Pengiriman::with(['pesanan.detailPesanan.produk.penjual.user', 'pesanan.user', 'kurir.user', 'pesanan.detailPesanan', 'pesanan.riwayat'])
            ->where('id_pengiriman', $id)
            ->firstOrFail();

        return view('admin.services.pengiriman-show', compact('pengiriman'));
    }
}
