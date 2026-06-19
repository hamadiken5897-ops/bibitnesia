<?php

namespace App\Http\Controllers\Kurir;
use App\Models\Kurir;
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
            'status_pengiriman' => 'diproses',
            'tanggal_pengiriman' => now(),
        ]);

        $pengiriman->pesanan->update([
            'status_pesanan' => 'Pesanan dalam pengiriman',
        ]);

        return redirect()->route('kurir.status-pengiriman.index')->with('success', 'Pengiriman diterima');
    }

    // 📦 KONFIRMASI SELESAI
    public function selesai(Request $request, $id)
    {
        $pengiriman = Pengiriman::findOrFail($id);

        $pengiriman->update([
            'status_pengiriman' => 'selesai',
        ]);

        $pengiriman->pesanan->update([
            'status_pesanan' => 'Sampai Tujuan',
        ]);

        return back()->with('success', 'Pengiriman selesai');
    }

    public function statusIndex()
    {
        $kurirId = auth()->user()->kurir->id_kurir;

        $pengiriman = Pengiriman::where('id_kurir', $kurirId)
            ->whereIn('status_pengiriman', ['diproses', 'dikirim'])
            ->orderBy('created_at')
            ->get();

        return view('kurir.status-pengiriman.index', compact('pengiriman'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pengiriman' => 'required|in:diproses,dikirim,selesai',
        ]);

        $kurirId = auth()->user()->kurir->id_kurir;

        $pengiriman = Pengiriman::where('id_pengiriman', $id)->where('id_kurir', $kurirId)->firstOrFail();

        $pengiriman->update([
            'status_pengiriman' => $request->status_pengiriman,
        ]);

        // Sinkron ke pesanan
        if ($request->status_pengiriman === 'selesai') {
            $pengiriman->pesanan->update([
                'status_pesanan' => 'Sampai Tujuan',
            ]);
        }

        return back()->with('success', 'Status pengiriman diperbarui');
    }
    public function riwayat()
    {
        $kurirId = auth()->user()->kurir->id_kurir;

        $pengiriman = Pengiriman::where('id_kurir', $kurirId)
            ->where('status_pengiriman', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('kurir.riwayat.index', compact('pengiriman'));
    }
}
