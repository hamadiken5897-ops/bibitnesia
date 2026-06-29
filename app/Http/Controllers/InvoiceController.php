<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function show($id_pesanan)
    {
        // Ambil pesanan milik user login
        $pesanan = DB::table('pesanans')
            ->where('id_pesanan', $id_pesanan)
            ->where('id_user', auth()->user()->id_user)
            ->first();

        if (!$pesanan) {
            abort(404);
        }

        // Ambil pembayaran
        $pembayaran = DB::table('pembayarans')->where('id_pesanan', $id_pesanan)->first();

        if (!$pembayaran) {
            abort(404);
        }

        // Cek expired otomatis (MVP logic)
        if ($pembayaran->status_validasi === 'pending' && $pembayaran->expired_at && now()->gt($pembayaran->expired_at)) {
            DB::table('pembayarans')
                ->where('id_pembayaran', $pembayaran->id_pembayaran)
                ->update([
                    'status_validasi' => 'expired',
                    'updated_at' => now(),
                ]);

            DB::table('pesanans')
                ->where('id_pesanan', $id_pesanan)
                ->update([
                    'status_pesanan' => 'Dibatalkan',
                    'updated_at' => now(),
                ]);

            // refresh data
            $pembayaran->status_validasi = 'expired';
            $pesanan->status = 'dibatalkan';
        }

        // Ambil client key
        $pengaturan = \App\Models\PengaturanPembayaran::first();
        $clientKey = $pengaturan ? $pengaturan->midtrans_client_key : '';

        return view('marketplace.invoice', compact('pesanan', 'pembayaran', 'clientKey'));
    }
}
