<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;

class KurirController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::orderBy('created_at', 'desc')->get();

        return view('kurir.inbox', compact('pesanan'));
    }

    public function detail($id)
    {
        $pesanan = Pesanan::with('detail')->findOrFail($id);

        // Update status jika belum dibaca
        if ($pesanan->status == 'baru') {
            $pesanan->update(['status' => 'dibaca']);
        }

        return view('kurir.inbox-detail', compact('pesanan'));
    }

    public function selesai($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update(['status' => 'selesai']);

        return redirect()->route('kurir.inbox')->with('success', 'Pesanan selesai');
    }
}
