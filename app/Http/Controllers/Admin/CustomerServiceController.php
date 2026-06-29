<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Komplain;
use App\Models\Banned;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CustomerServiceController extends Controller
{
    /**
     * Tampilkan data semua komplain dan daftar pengguna dibanned.
     */
    public function index()
    {
        $komplains = Komplain::with(['user', 'terlapor', 'produk', 'ulasan'])->latest()->get();
        $banneds = Banned::with('user')->orderBy('tgl_banned', 'desc')->get();

        return view('admin.customer_service.index', compact('komplains', 'banneds'));
    }

    /**
     * Tampilkan detail laporan untuk dianalisis
     */
    public function show($id)
    {
        $komplain = Komplain::with([
            'user', 
            'terlapor.pesanDiterima.pengirim', 
            'terlapor.pesanTerkirim.penerima',
            'produk.penjual.user.pesanDiterima.pengirim',
            'produk.penjual.user.pesanTerkirim.penerima',
            'produk.ulasans.user',
            'ulasan.produk.ulasans.user',
            'ulasan.user'
        ])->findOrFail($id);

        // Cari riwayat laporan terlapor jika ada (jika terlapor diset)
        $riwayatLaporan = [];
        if ($komplain->id_terlapor || ($komplain->produk && $komplain->produk->id_user) || ($komplain->ulasan && $komplain->ulasan->id_user)) {
            $targetUserId = $komplain->id_terlapor 
                ?? ($komplain->produk ? $komplain->produk->penjual->id_user : null) 
                ?? ($komplain->ulasan ? $komplain->ulasan->id_user : null);

            if ($targetUserId) {
                $riwayatLaporan = Komplain::where(function($q) use ($targetUserId) {
                    $q->where('id_terlapor', $targetUserId)
                      ->orWhereHas('produk.penjual', function($q2) use ($targetUserId) {
                          $q2->where('id_user', $targetUserId);
                      })
                      ->orWhereHas('ulasan', function($q3) use ($targetUserId) {
                          $q3->where('id_user', $targetUserId);
                      });
                })->where('id_komplain', '!=', $komplain->id_komplain)->latest()->get();
            }
        }

        return view('admin.customer_service.show', compact('komplain', 'riwayatLaporan'));
    }

    /**
     * Memperbarui status komplain.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:MENUNGGU,DIPROSES,SELESAI,DITOLAK',
            'catatan_admin' => 'nullable|string',
        ]);

        $komplain = Komplain::findOrFail($id);
        $komplain->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * Memberikan peringatan ke user
     */
    public function warnUser(Request $request, $id)
    {
        $komplain = Komplain::findOrFail($id);
        
        $request->validate([
            'id_user_target' => 'required|string',
            'peringatan_teks' => 'required|string',
        ]);

        $user = User::findOrFail($request->id_user_target);
        
        $user->update([
            'peringatan_teks' => $request->peringatan_teks,
            'tgl_peringatan' => now(),
        ]);

        $komplain->update([
            'status' => 'SELESAI',
            'catatan_admin' => 'Diberikan peringatan: ' . $request->peringatan_teks,
        ]);

        \App\Models\NotifikasiUser::create([
            'id_user' => $user->id_user,
            'judul' => 'Peringatan Admin',
            'pesan' => 'Anda mendapatkan peringatan: ' . $request->peringatan_teks,
        ]);

        return back()->with('success', 'Peringatan berhasil dikirim ke pengguna.');
    }

    /**
     * Memblokir (Banned) pihak terlapor dari komplain.
     */
    public function banUser(Request $request, $id)
    {
        $komplain = Komplain::findOrFail($id);
        $id_terlapor = $request->input('id_user_target');

        if (!$id_terlapor) {
            return back()->with('error', 'Target blokir tidak valid.');
        }

        $request->validate([
            'banned_status' => 'required|in:SEMENTARA,PERMANEN',
            'tgl_berakhir' => 'nullable|required_if:banned_status,SEMENTARA|date|after:now',
            'alasan' => 'required|string',
        ]);

        // 1. Dapatkan user terlapor
        $user = User::findOrFail($id_terlapor);

        // 2. Ubah status akun user jadi BANNED
        $user->update([
            'status_akun' => 'BANNED',
        ]);

        // 3. Masukkan record ke tabel banneds dengan waktu spesifik jam/menit
        Banned::create([
            'id_banned' => 'BND-' . strtoupper(Str::random(10)),
            'id_user' => $id_terlapor,
            'status' => $request->banned_status,
            'tgl_banned' => now()->toDateTimeString(),
            'tgl_berakhir' => $request->banned_status === 'SEMENTARA' ? Carbon::parse($request->tgl_berakhir)->toDateTimeString() : null,
            'alasan' => $request->alasan,
        ]);

        // 4. Ubah status komplain menjadi SELESAI
        $komplain->update([
            'status' => 'SELESAI',
            'catatan_admin' => 'Tindakan diambil: Terlapor telah diblokir (banned) dengan alasan: ' . $request->alasan,
        ]);

        return back()->with('success', 'User berhasil dibanned dan status komplain diselesaikan.');
    }

    /**
     * Membatalkan/mengangkat status banned pengguna (Unban).
     */
    public function unbanUser($id_user)
    {
        $user = User::findOrFail($id_user);
        
        // 1. Kembalikan status akun menjadi AKTIF
        $user->update([
            'status_akun' => 'AKTIF',
        ]);

        // 2. Hapus data banned terkait dari tabel banneds
        Banned::where('id_user', $id_user)->delete();

        return back()->with('success', 'Status banned untuk user ' . $user->nama . ' telah berhasil dicabut.');
    }
}
