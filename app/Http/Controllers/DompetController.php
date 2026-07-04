<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletHistory;
use Illuminate\Support\Facades\DB;

class DompetController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Ambil riwayat mutasi dompet
        $histories = WalletHistory::where('user_id', $user->id_user)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('user.dompet.index', compact('user', 'histories'));
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:10000',
            'nama_bank' => 'required|string',
            'no_rekening' => 'required|string',
            'nama_pemilik_rekening' => 'required|string',
        ]);

        $user = auth()->user();
        $jumlahTarik = $request->jumlah;

        if ($user->saldo < $jumlahTarik) {
            return back()->with('error', 'Saldo Anda tidak mencukupi untuk penarikan ini.');
        }

        DB::transaction(function () use ($user, $jumlahTarik, $request) {
            // 1. Kurangi saldo user
            $user->decrement('saldo', $jumlahTarik);

            // 2. Catat ke history wallet
            WalletHistory::create([
                'user_id' => $user->id_user,
                'jumlah' => $jumlahTarik,
                'tipe' => 'keluar',
                'deskripsi' => 'Penarikan dana ke ' . $request->nama_bank . ' - ' . $request->no_rekening,
            ]);

            // 3. Masukkan ke tabel penarikan_saldos agar diproses admin
            DB::table('penarikan_saldos')->insert([
                'user_id' => $user->id_user,
                'role' => 'pembeli',
                'nama_bank' => $request->nama_bank,
                'no_rekening' => $request->no_rekening,
                'nama_pemilik_rekening' => $request->nama_pemilik_rekening,
                'jumlah_penarikan' => $jumlahTarik,
                'status' => 'pending',
                'tgl_pengajuan' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()->route('user.dompet.index')->with('success', 'Pengajuan penarikan dana berhasil dibuat dan sedang diproses admin.');
    }

    public function updateRekening(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string',
            'no_rekening' => 'required|string',
            'nama_pemilik_rekening' => 'required|string',
        ]);

        $user = auth()->user();
        $user->update([
            'nama_bank' => $request->nama_bank,
            'no_rekening' => $request->no_rekening,
            'nama_pemilik_rekening' => $request->nama_pemilik_rekening,
        ]);

        return back()->with('success', 'Pengaturan rekening tujuan berhasil disimpan.');
    }
}
