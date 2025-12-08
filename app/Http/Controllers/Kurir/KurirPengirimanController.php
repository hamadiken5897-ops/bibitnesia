<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class KurirPengirimanController extends Controller
{
    public function index()
    {
        $pengiriman = Pesanan::orderBy('created_at', 'desc')->get();

        return view('kurir.pengiriman', [
            'pengiriman' => $pengiriman,
            'total' => $pengiriman->count(),
            'sedang_diantar' => $pengiriman->where('status', 'diantar')->count(),
            'terkirim' => $pengiriman->where('status', 'selesai')->count(),
            'gagal' => $pengiriman->where('status', 'gagal')->count(),
        ]);
    }

    public function show($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        return view('kurir.pengiriman-detail', compact('pesanan'));
    }

    // endpoint untuk update status (via POST)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status = $request->status;
        $pesanan->save();

        return back()->with('success', 'Status berhasil diperbarui');
    }
}
