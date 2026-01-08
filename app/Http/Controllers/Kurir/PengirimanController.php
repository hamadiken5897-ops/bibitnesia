<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Pengiriman;
use Illuminate\Http\Request;

class PengirimanController extends Controller
{
    // 📥 INBOX KURIR
    public function index()
    {
        $kurirId = auth()->user()->kurir->id_kurir;

        $pengiriman = Pengiriman::where('id_kurir', $kurirId)
            ->whereIn('status_pengiriman', ['dikemas', 'dikirim'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('kurir.pengiriman.index', compact('pengiriman'));
    }

    // ✅ TERIMA PENGIRIMAN
    public function accept($id)
    {
        $pengiriman = Pengiriman::findOrFail($id);

        $pengiriman->update([
            'status_pengiriman' => 'dikirim',
        ]);

        $pengiriman->pesanan->update([
            'status_pesanan' => 'dikirim',
        ]);

        return back()->with('success', 'Pengiriman diterima');
    }

    // 📦 KONFIRMASI SELESAI
    public function selesai(Request $request, $id)
    {
        $pengiriman = Pengiriman::findOrFail($id);

        $pengiriman->update([
            'status_pengiriman' => 'selesai',
        ]);

        $pengiriman->pesanan->update([
            'status_pesanan' => 'selesai',
        ]);

        return back()->with('success', 'Pengiriman selesai');
    }
}
