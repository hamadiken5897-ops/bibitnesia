<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\LaporanPenjual;
use App\Models\Pesanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    public function index()
    {
        $penjual = auth()->user()->penjual;
        
        if (!$penjual) {
            abort(403, 'Anda bukan penjual');
        }

        $id_penjual = $penjual->id_penjual;

        $total_pemasukan = LaporanPenjual::where('id_penjual', $id_penjual)->sum('jumlah');
        $total_pesanan   = LaporanPenjual::where('id_penjual', $id_penjual)->distinct('id_pesanan')->count('id_pesanan');

        $laporan = LaporanPenjual::select('id_pesanan', DB::raw('SUM(jumlah) as total_jumlah'), DB::raw('SUM(komisi) as total_komisi'), DB::raw('MAX(created_at) as tgl_masuk'))
            ->where('id_penjual', $id_penjual)
            ->groupBy('id_pesanan')
            ->orderBy('tgl_masuk', 'desc')
            ->paginate(10);

        $pesananIds = $laporan->pluck('id_pesanan');
        $pesananDetails = Pesanan::with(['detailPesanan.produk' => function($q) use ($id_penjual) {
            $q->where('id_penjual', $id_penjual);
        }])->whereIn('id_pesanan', $pesananIds)->get()->keyBy('id_pesanan');

        $riwayatPenarikan = \App\Models\PenarikanSaldo::where('user_id', $id_penjual)
            ->where('role', 'penjual')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Ambil persentase komisi platform
        $pengaturan = \App\Models\PengaturanPembayaran::first();
        $komisiPersen = $pengaturan ? $pengaturan->biaya_layanan_persen : 5.00;

        return view('penjual.saldo.index', compact(
            'penjual',
            'total_pemasukan',
            'total_pesanan',
            'laporan',
            'pesananDetails',
            'riwayatPenarikan',
            'komisiPersen'
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

        $penjual = auth()->user()->penjual;
        $penjual->update([
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

        $penjual = auth()->user()->penjual;
        $jumlah = $request->jumlah_penarikan;
        $tujuan = $request->tujuan_penarikan;

        // Validasi rekening kosong berdasarkan tujuan
        if ($tujuan === 'bank') {
            if (empty($penjual->nama_bank) || empty($penjual->no_rekening)) {
                return back()->with('error', 'Silakan lengkapi informasi Rekening Bank terlebih dahulu.');
            }
            $targetBank = $penjual->nama_bank;
            $targetRekening = $penjual->no_rekening;
            $targetPemilik = $penjual->nama_pemilik_rekening;
        } else {
            if (empty($penjual->ewallet_name) || empty($penjual->ewallet_phone)) {
                return back()->with('error', 'Silakan lengkapi informasi E-Wallet terlebih dahulu.');
            }
            $targetBank = $penjual->ewallet_name;
            $targetRekening = $penjual->ewallet_phone;
            $targetPemilik = $penjual->ewallet_owner;
        }

        // Validasi kecukupan saldo
        if ($penjual->saldo < $jumlah) {
            return back()->with('error', 'Saldo Anda tidak mencukupi untuk melakukan penarikan sebesar Rp ' . number_format($jumlah, 0, ',', '.'));
        }

        // Mulai transaksi database
        DB::transaction(function () use ($penjual, $jumlah, $targetBank, $targetRekening, $targetPemilik) {
            // 1. Kurangi saldo penjual
            $penjual->decrement('saldo', $jumlah);

            // 2. Buat record penarikan
            \App\Models\PenarikanSaldo::create([
                'user_id' => $penjual->id_penjual,
                'role' => 'penjual',
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
