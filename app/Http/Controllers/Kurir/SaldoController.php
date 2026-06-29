<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\LaporanKurir;
use App\Models\Pengiriman;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    public function index()
    {
        $kurir = auth()->user()->kurir;
        
        if (!$kurir) {
            abort(403, 'Anda bukan kurir');
        }

        $id_kurir = $kurir->id_kurir;

        $total_pemasukan = LaporanKurir::where('id_kurir', $id_kurir)->sum('jumlah');
        $total_pengiriman = LaporanKurir::where('id_kurir', $id_kurir)->distinct('id_pesanan')->count('id_pesanan');

        $laporan = LaporanKurir::select('id_pesanan', DB::raw('SUM(jumlah) as total_jumlah'), DB::raw('MAX(created_at) as tgl_masuk'))
            ->where('id_kurir', $id_kurir)
            ->groupBy('id_pesanan')
            ->orderBy('tgl_masuk', 'desc')
            ->paginate(10);

        $pesananIds = $laporan->pluck('id_pesanan');
        
        $pengirimanDetails = Pengiriman::with('pesanan.detailPesanan.produk')
            ->whereIn('id_pesanan', $pesananIds)
            ->where('id_kurir', $id_kurir)
            ->get()
            ->keyBy('id_pesanan');

        $riwayatPenarikan = \App\Models\PenarikanSaldo::where('user_id', $id_kurir)
            ->where('role', 'kurir')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kurir.saldo.index', compact(
            'kurir',
            'total_pemasukan',
            'total_pengiriman',
            'laporan',
            'pengirimanDetails',
            'riwayatPenarikan'
        ));
    }

    public function updateRekening(Request $request)
    {
        $request->validate([
            'nama_bank' => 'nullable|string|max:50',
            'no_rekening' => 'nullable|string|max:50',
            'nama_pemilik_rekening' => 'nullable|string|max:100',
            'ewallet_name' => 'nullable|string|max:50',
            'ewallet_phone' => 'nullable|string|max:50',
            'ewallet_owner' => 'nullable|string|max:100',
        ]);

        $kurir = auth()->user()->kurir;
        $kurir->update([
            'nama_bank' => $request->nama_bank,
            'no_rekening' => $request->no_rekening,
            'nama_pemilik_rekening' => $request->nama_pemilik_rekening,
            'ewallet_name' => $request->ewallet_name,
            'ewallet_phone' => $request->ewallet_phone,
            'ewallet_owner' => $request->ewallet_owner,
        ]);

        return back()->with('success', 'Informasi rekening & e-wallet berhasil disimpan!');
    }

    public function tarikSaldo(Request $request)
    {
        $request->validate([
            'jumlah_penarikan' => 'required|numeric|min:10000',
            'tujuan_penarikan' => 'required|in:bank,ewallet',
        ]);

        $kurir = auth()->user()->kurir;
        $jumlah = $request->jumlah_penarikan;
        $tujuan = $request->tujuan_penarikan;

        // Validasi rekening kosong berdasarkan tujuan
        if ($tujuan === 'bank') {
            if (empty($kurir->nama_bank) || empty($kurir->no_rekening)) {
                return back()->with('error', 'Silakan lengkapi informasi Rekening Bank terlebih dahulu.');
            }
            $targetBank = $kurir->nama_bank;
            $targetRekening = $kurir->no_rekening;
            $targetPemilik = $kurir->nama_pemilik_rekening;
        } else {
            if (empty($kurir->ewallet_name) || empty($kurir->ewallet_phone)) {
                return back()->with('error', 'Silakan lengkapi informasi E-Wallet terlebih dahulu.');
            }
            $targetBank = $kurir->ewallet_name;
            $targetRekening = $kurir->ewallet_phone;
            $targetPemilik = $kurir->ewallet_owner;
        }

        // Validasi kecukupan saldo
        if ($kurir->saldo < $jumlah) {
            return back()->with('error', 'Saldo Anda tidak mencukupi untuk melakukan penarikan sebesar Rp ' . number_format($jumlah, 0, ',', '.'));
        }

        // Mulai transaksi database
        DB::transaction(function () use ($kurir, $jumlah, $targetBank, $targetRekening, $targetPemilik) {
            // 1. Kurangi saldo kurir
            $kurir->decrement('saldo', $jumlah);

            // 2. Buat record penarikan
            \App\Models\PenarikanSaldo::create([
                'user_id' => $kurir->id_kurir,
                'role' => 'kurir',
                'nama_bank' => $targetBank,
                'no_rekening' => $targetRekening,
                'nama_pemilik_rekening' => $targetPemilik,
                'jumlah_penarikan' => $jumlah,
                'status' => 'pending',
                'tgl_pengajuan' => now(),
            ]);
        });

        return back()->with('success', 'Pengajuan penarikan saldo berhasil dikirim dan sedang diproses!');
    }
}
