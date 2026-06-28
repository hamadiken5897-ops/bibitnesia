<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanPembayaran;
use Illuminate\Http\Request;

class PengaturanPembayaranController extends Controller
{
    public function index()
    {
        // Ambil pengaturan pertama atau buat instance kosong jika belum ada
        $pengaturan = PengaturanPembayaran::first() ?? new PengaturanPembayaran();
        return view('admin.pengaturan.pembayaran', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'bank_name' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:50',
            'bank_owner' => 'nullable|string|max:100',
            'ewallet_name' => 'nullable|string|max:100',
            'ewallet_phone' => 'nullable|string|max:20',
            'ewallet_owner' => 'nullable|string|max:100',
            'midtrans_server_key' => 'nullable|string|max:255',
            'midtrans_client_key' => 'nullable|string|max:255',
            'biaya_layanan_persen' => 'nullable|numeric|min:0|max:100',
            'card_theme' => 'nullable|string|max:50',
        ]);

        $pengaturan = PengaturanPembayaran::first();
        
        $data = $request->except('_token');
        $data['midtrans_is_active'] = $request->has('midtrans_is_active');

        if ($pengaturan) {
            $pengaturan->update($data);
        } else {
            PengaturanPembayaran::create($data);
        }

        return redirect()->route('admin.pengaturan.pembayaran')
            ->with('success', 'Pengaturan Pembayaran berhasil diperbarui!');
    }
}
