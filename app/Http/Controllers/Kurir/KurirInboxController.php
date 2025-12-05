<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class KurirInboxController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::orderBy('created_at', 'desc')->get();

        return view('kurir.inbox', [
            'pesanan' => $pesanan,
            'total' => $pesanan->count(),
            'unread' => $pesanan->whereNull('read_at')->count(),
            'today' => $pesanan->where('created_at', '>=', now()->startOfDay())->count()
        ]);
    }

    public function show($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if (!$pesanan->read_at) {
            $pesanan->update(['read_at' => now()]);
        }

        return view('kurir.inbox-detail', compact('pesanan'));
    }
}
