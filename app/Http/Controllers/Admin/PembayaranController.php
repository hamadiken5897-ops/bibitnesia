<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    // Menampilkan semua data pembayaran
    public function index()
    {
        // Data Transaksi Masuk
        $pembayarans = Pembayaran::with(['pesanan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // Data Payouts
        $penarikanSaldos = \App\Models\PenarikanSaldo::orderBy('created_at', 'desc')->paginate(10);

        // DATA UNTUK TAB KEUANGAN
        // 1. Total Uang Masuk (Hanya yang Paid/Valid)
        $totalUangMasuk = Pembayaran::whereIn('status_validasi', ['paid', 'valid', 'sudah_bayar', 'dibayar'])->sum('total_bayar');
        
        // 2. Daftar Saldo Penjual & Kurir
        $penjuals = \App\Models\Penjual::where('saldo', '>', 0)->orderBy('saldo', 'desc')->get();
        $kurirs = \App\Models\Kurir::where('saldo', '>', 0)->orderBy('saldo', 'desc')->get();
        
        // 3. Saldo Mitra Tertahan (Escrow)
        $saldoTertahan = $penjuals->sum('saldo') + $kurirs->sum('saldo');
        
        // 4. Total Payout Sukses
        $totalPayoutSukses = \App\Models\PenarikanSaldo::where('status', 'selesai')->sum('jumlah_penarikan');
        
        // 5. Total Komisi (Keuntungan Bersih Admin)
        $totalKomisi = \App\Models\LaporanPenjual::sum('komisi');
        
        $netProfit = $totalUangMasuk - $saldoTertahan - $totalPayoutSukses;

        $pengaturan = \App\Models\PengaturanPembayaran::first();
        $komisiPersen = $pengaturan ? $pengaturan->biaya_layanan_persen : 5.00;

        return view('admin.manajemen.pembayaran', compact(
            'pembayarans', 
            'penarikanSaldos',
            'totalUangMasuk',
            'saldoTertahan',
            'netProfit',
            'totalKomisi',
            'komisiPersen',
            'penjuals',
            'kurirs'
        ));
    }

    // Menampilkan detail pembayaran
    public function show($id)
    {
        $pembayaran = Pembayaran::with(['user', 'pesanan'])->findOrFail($id);

        return view('admin.manajemen.pembayaran.show', compact('pembayaran'));
    }

    // Update status penarikan saldo (Payout)
    public function updatePayout(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:selesai,ditolak',
            'alasan_penolakan' => 'nullable|string'
        ]);

        $payout = \App\Models\PenarikanSaldo::findOrFail($id);

        // Hanya bisa memproses jika belum selesai atau ditolak
        if (in_array($payout->status, ['selesai', 'ditolak'])) {
            return back()->with('error', 'Penarikan ini sudah diproses sebelumnya.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($payout, $request) {
            $statusBaru = $request->status;
            $payout->status = $statusBaru;
            $payout->save();

            $judulNotif = '';
            $pesanNotif = '';
            $idUserForNotif = null;

            if ($statusBaru === 'selesai') {
                $judulNotif = '✅ Penarikan Saldo Berhasil';
                $pesanNotif = 'Hore! Penarikan saldo sebesar Rp ' . number_format($payout->jumlah_penarikan, 0, ',', '.') . ' telah berhasil ditransfer ke rekening ' . strtoupper($payout->nama_bank) . ' Anda.';
            } else if ($statusBaru === 'ditolak') {
                $judulNotif = '❌ Penarikan Saldo Ditolak';
                $pesanNotif = 'Mohon maaf, penarikan saldo sebesar Rp ' . number_format($payout->jumlah_penarikan, 0, ',', '.') . ' ditolak. Alasan: ' . ($request->alasan_penolakan ?? 'Silakan hubungi Admin.');
                
                // Kembalikan dana ke user
                if ($payout->role === 'penjual') {
                    \App\Models\Penjual::where('id_penjual', $payout->user_id)->increment('saldo', $payout->jumlah_penarikan);
                } else if ($payout->role === 'kurir') {
                    \App\Models\Kurir::where('id_kurir', $payout->user_id)->increment('saldo', $payout->jumlah_penarikan);
                }
            }

            // Get correct id_user for notification
            if ($payout->role === 'penjual') {
                $penjual = \App\Models\Penjual::where('id_penjual', $payout->user_id)->first();
                $idUserForNotif = $penjual ? $penjual->id_user : null;
            } else {
                $kurir = \App\Models\Kurir::where('id_kurir', $payout->user_id)->first();
                $idUserForNotif = $kurir ? $kurir->id_user : null;
            }

            // Kirim notifikasi real-time jika id_user ditemukan
            if ($idUserForNotif) {
                \App\Models\NotifikasiUser::create([
                    'id_user' => $idUserForNotif,
                    'judul' => $judulNotif,
                    'pesan' => $pesanNotif,
                    'redirect_url' => $payout->role === 'penjual' ? '/penjual/saldo' : '/kurir/saldo',
                    'is_read' => false
                ]);
            }
        });

        $msg = $request->status === 'selesai' ? 'Penarikan saldo berhasil disetujui!' : 'Penarikan saldo telah ditolak & uang dikembalikan.';
        return back()->with('success', $msg);
    }
}
