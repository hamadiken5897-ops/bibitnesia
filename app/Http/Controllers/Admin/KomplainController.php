<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Komplain;
use App\Models\Banned;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KomplainController extends Controller
{
    /**
     * Tampilkan data semua komplain dan daftar pengguna dibanned.
     */
    public function index()
    {
        $komplains = Komplain::with(['user', 'pesanan', 'terlapor'])->latest()->get();
        $banneds = Banned::with('user')->orderBy('tgl_banned', 'desc')->get();

        return view('admin.services.komplain', compact('komplains', 'banneds'));
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

        return back()->with('success', 'Status komplain berhasil diperbarui.');
    }

    /**
     * Memblokir (Banned) pihak terlapor dari komplain.
     */
    public function banUser(Request $request, $id)
    {
        $komplain = Komplain::findOrFail($id);
        $id_terlapor = $komplain->id_terlapor;

        if (!$id_terlapor) {
            return back()->with('error', 'Tidak ada pihak terlapor pada komplain ini.');
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

        return back()->with('success', 'User terlapor berhasil dibanned dan status komplain diselesaikan.');
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
