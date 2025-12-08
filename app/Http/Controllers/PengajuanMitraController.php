<?php

namespace App\Http\Controllers;

use App\Models\PengajuanMitra;
use App\Models\User;
use Illuminate\Http\Request;

class PengajuanMitraController extends Controller
{
    /**
     * Halaman daftar pengajuan mitra untuk admin
     */
    public function index()
    {
        $penjual = PengajuanMitra::where('role_pengajuan', 'penjual')->where('status', 'Menunggu')->latest()->get();

        $kurir = PengajuanMitra::where('role_pengajuan', 'kurir')->where('status', 'Menunggu')->latest()->get();

        $totalAntrian = PengajuanMitra::where('status', 'Menunggu')->count();

        return view('admin.services.pengajuan.index', compact('penjual', 'kurir', 'totalAntrian'));
    }

    /**
     * Store pengajuan mitra dari user
     */
    public function store(Request $request)
    {
        $role = $request->role_pengajuan;
    
        // cek sudah daftar
        $cek = PengajuanMitra::where('id_user', auth()->user()->id_user)
            ->where('status', 'Menunggu')
            ->first();

        if ($cek) {
            return redirect()->route('mitra.succes')->with('success', 'Anda sudah mengirim pengajuan. Harap menunggu verifikasi admin.');
        }

        // VALIDASI DASAR
        $rules = [
            'role_pengajuan' => 'required|in:penjual,kurir',
            'ktp' => 'required|image|max:2048',
            'foto_selfie' => 'required|image|max:2048',
            'no_rekening' => 'required|string',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
        ];

        // VALIDASI ROLE KURIR
        if ($role === 'kurir') {
            $rules += [
                'sim' => 'required|image|max:2048',
                'stnk' => 'required|image|max:2048',
                'skck' => 'required|image|max:2048',
                'foto_kendaraan' => 'required|image|max:2048',
                'tipe_kendaraan' => 'required|in:motor,mobil,truk,cargo',
                'merek_kendaraan' => 'required|string',
            ];
        }

        // VALIDASI ROLE PENJUAL
        if ($role === 'penjual') {
            $rules += [
                'foto_kebun' => 'required|image|max:2048',
                'portofolio' => 'nullable|file|mimes:pdf,doc,docx',
                'deskripsi' => 'required|string',
                'alamat_kebun' => 'required|string',
            ];
        }

        $validated = $request->validate($rules);

        // === UPLOAD FILE WAJIB (disimpan di storage public) ===
        $validated['ktp'] = $request->file('ktp')->store('mitra/ktp', 'public');
        $validated['foto_selfie'] = $request->file('foto_selfie')->store('mitra/selfie', 'public');

        // === UPLOAD KHUSUS KURIR ===
        if ($role === 'kurir') {
            $validated['sim'] = $request->file('sim')->store('mitra/sim', 'public');
            $validated['stnk'] = $request->file('stnk')->store('mitra/stnk', 'public');
            $validated['skck'] = $request->file('skck')->store('mitra/skck', 'public');
            $validated['foto_kendaraan'] = $request->file('foto_kendaraan')->store('mitra/kendaraan', 'public');
        }

        // === UPLOAD KHUSUS PENJUAL ===
        if ($role === 'penjual') {
            $validated['foto_kebun'] = $request->file('foto_kebun')->store('mitra/kebun', 'public');

            if ($request->hasFile('portofolio')) {
                $validated['portofolio'] = $request->file('portofolio')->store('mitra/portofolio', 'public');
            }
        }

        // SET ID USER PENGAJU
        $validated['id_user'] = auth()->user()->id_user;

        // SIMPAN KE DATABASE
        PengajuanMitra::create($validated);

        return redirect()->route('mitra.succes')->with('success', 'Pengajuan mitra berhasil dikirim.');
    }

    /**
     * Detail pengajuan
     */
    public function show($id)
    {
        $pengajuan = PengajuanMitra::findOrFail($id);
        return view('admin.services.pengajuan.show', compact('pengajuan'));
    }

    /**
     * Approve pengajuan
     */
    public function approve($id)
    {
        $pengajuan = PengajuanMitra::findOrFail($id);
        $user = User::where('id_user', $pengajuan->id_user)->first();

        $user->role = $pengajuan->role_pengajuan;
        $user->save();

        $pengajuan->update([
            'status' => 'Disetujui',
            'catatan_admin' => null,
        ]);

        return back()->with('success', 'Pengajuan mitra berhasil disetujui.');
    }

    /**
     * Reject pengajuan
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string',
        ]);

        $pengajuan = PengajuanMitra::findOrFail($id);

        $pengajuan->update([
            'status' => 'Ditolak',
            'catatan_admin' => $request->catatan_admin,
        ]);

        return back()->with('success', 'Pengajuan mitra ditolak.');
    }

    /**
     * Hapus pengajuan
     */
    public function destroy($id)
    {
        $pengajuan = PengajuanMitra::findOrFail($id);

        $pengajuan->delete();

        return back()->with('success', 'Pengajuan mitra berhasil dihapus.');
    }
}
