<?php

namespace App\Http\Controllers\Kurir;
use App\Models\Kurir;
use App\Http\Controllers\Controller;
use App\Models\Pengiriman;
use Illuminate\Http\Request;

class PengirimanController extends Controller
{
    // 📥 INBOX KURIR (Permintaan Penjemputan)
    public function permintaan()
    {
        $kurirId = auth()->user()->kurir->id_kurir;

        $pengiriman = Pengiriman::where('id_kurir', $kurirId)
            ->where('status_pengiriman', 'menunggu_kurir')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('kurir.permintaan.index', compact('pengiriman'));
    }

    // ✅ TERIMA TUGAS PENJEMPUTAN
    public function terima($id)
    {
        $kurirId = auth()->user()->kurir->id_kurir;
        $pengiriman = Pengiriman::where('id_pengiriman', $id)->where('id_kurir', $kurirId)->firstOrFail();

        $pengiriman->update([
            'status_pengiriman' => 'diproses',
        ]);

        \App\Models\RiwayatPesanan::create([
            'id_pesanan' => $pengiriman->id_pesanan,
            'status' => 'Kurir Menuju Lokasi',
            'deskripsi' => 'Kurir telah menerima tugas dan sedang dalam perjalanan untuk menjemput paket dari penjual.'
        ]);

        return redirect()->route('kurir.status-pengiriman.index')->with('success', 'Tugas penjemputan diterima');
    }

    // 🚀 MULAI PENGIRIMAN
    public function mulaiKirim($id)
    {
        $kurirId = auth()->user()->kurir->id_kurir;
        $pengiriman = Pengiriman::where('id_pengiriman', $id)->where('id_kurir', $kurirId)->firstOrFail();

        \Illuminate\Support\Facades\DB::transaction(function () use ($pengiriman) {
            $pengiriman->update([
                'status_pengiriman' => 'dikirim',
                'tanggal_pengiriman' => now(),
            ]);

            $pengiriman->pesanan->update([
                'status_pesanan' => 'Pesanan dalam pengiriman',
            ]);

            \App\Models\RiwayatPesanan::create([
                'id_pesanan' => $pengiriman->id_pesanan,
                'status' => 'Dalam Pengiriman',
                'deskripsi' => 'Paket telah diambil oleh kurir dan sedang dalam proses pengiriman ke alamat tujuan.'
            ]);
        });

        return redirect()->route('kurir.status-pengiriman.index')->with('success', 'Status diubah menjadi Dalam Pengiriman');
    }

    // 📦 KONFIRMASI SELESAI
    public function selesai(Request $request, $id)
    {
        $kurirId = auth()->user()->kurir->id_kurir;
        $pengiriman = Pengiriman::where('id_pengiriman', $id)->where('id_kurir', $kurirId)->firstOrFail();

        \Illuminate\Support\Facades\DB::transaction(function () use ($pengiriman) {
            $pengiriman->update([
                'status_pengiriman' => 'selesai',
            ]);

            $pengiriman->pesanan->update([
                'status_pesanan' => 'Sampai Tujuan',
            ]);

            \App\Models\RiwayatPesanan::create([
                'id_pesanan' => $pengiriman->id_pesanan,
                'status' => 'Paket Diterima',
                'deskripsi' => 'Paket telah berhasil dikirim dan sampai di alamat tujuan.'
            ]);
        });

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

        // Alihkan ke metode spesifik untuk mencatat riwayat
        if ($request->status_pengiriman === 'dikirim') {
            return $this->mulaiKirim($id);
        } elseif ($request->status_pengiriman === 'selesai') {
            return $this->selesai($request, $id);
        }

        $pengiriman->update([
            'status_pengiriman' => $request->status_pengiriman,
        ]);

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
