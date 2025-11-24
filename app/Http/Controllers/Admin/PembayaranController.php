<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    // Menampilkan semua data pembayaran
    public function index()
    {
        $pembayarans = Pembayaran::with(['user', 'pesanan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.manajemen.pembayaran', compact('pembayarans'));
    }

    // Menampilkan detail pembayaran
    public function show($id)
    {
        $pembayaran = Pembayaran::with(['user', 'pesanan'])->findOrFail($id);

        return view('admin.manajemen.pembayaran.show', compact('pembayarans'));
    }
}
