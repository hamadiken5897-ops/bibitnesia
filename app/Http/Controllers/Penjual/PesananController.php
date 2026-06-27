<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\NotifikasiUser;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    /**
     * Menampilkan daftar pesanan masuk
     */
    public function index(Request $request)
    {
        $penjual = auth()->user()->penjual;
        if (!$penjual) {
            abort(403, 'Akun ini bukan penjual');
        }

        $status = $request->query('status', 'baru');

        $query = Pesanan::whereHas('detailPesanan.produk', function ($q) use ($penjual) {
                $q->where('id_penjual', $penjual->id_penjual);
            })
            ->with(['user', 'detailPesanan.produk', 'pengiriman'])
            ->orderBy('created_at', 'desc');

        if ($status == 'baru') {
            $query->where('status_pesanan', 'Menunggu konfirmasi penjual');
        } elseif ($status == 'perlu-dikirim') {
            $query->where('status_pesanan', 'Pesanan sedang diproses');
        } elseif ($status == 'dikirim') {
            $query->whereIn('status_pesanan', ['Menunggu penjemputan kurir', 'Pesanan dalam pengiriman']);
        } elseif ($status == 'selesai') {
            $query->whereIn('status_pesanan', ['Pesanan selesai', 'Pesanan Selesai'])
                  ->whereDate('updated_at', \Carbon\Carbon::today());
        } elseif ($status == 'dibatalkan') {
            $query->where('status_pesanan', 'Pesanan ditolak')
                  ->whereDate('updated_at', \Carbon\Carbon::today());
        }

        $pesanan = $query->get();

        return view('penjual.pesanan.index', compact('pesanan', 'status'));
    }

    /**
     * Menampilkan detail pesanan
     */
    public function show($id)
    {
        $pesanan = Pesanan::with(['user', 'detailPesanan.produk', 'pengiriman', 'riwayat'])
            ->where('id_pesanan', $id)
            ->firstOrFail();

        return view('penjual.pesanan.show', compact('pesanan'));
    }
    /**
     * Terima pesanan
     */
    public function accept($id)
    {
        $pesanan = Pesanan::with('detailPesanan.produk')->where('id_pesanan', $id)->firstOrFail();

        // Kurangi stok produk
        foreach ($pesanan->detailPesanan as $detail) {
            if ($detail->produk) {
                $detail->produk->decrement('stok', $detail->jumlah);
                
                // Jika stok habis, ubah status
                if ($detail->produk->fresh()->stok <= 0) {
                    $detail->produk->update(['status' => 'habis']);
                }
            }
        }

        $pesanan->update([
            'status_pesanan' => 'Pesanan sedang diproses', // Berubah menjadi sedang diproses (dikemas)
        ]);

        \App\Models\RiwayatPesanan::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'status' => 'Sedang Dikemas',
            'deskripsi' => 'Penjual telah menerima pesanan dan sedang mengemas pesanan Anda.'
        ]);

        // Kirim notifikasi ke pembeli
        NotifikasiUser::create([
            'id_user' => $pesanan->id_user,
            'judul' => 'Pesanan Diterima',
            'pesan' => 'Pesanan #' . $pesanan->kode_invoice . ' sedang dikemas oleh penjual.',
            'type' => 'success',
        ]);

        return redirect()->route('penjual.pesanan.index', ['status' => 'perlu-dikirim'])->with('success', 'Pesanan berhasil diterima dan siap dikemas.');
    }

    /**
     * Kirim pesanan (Input Resi)
     */
    public function kirim(Request $request, $id)
    {
        $request->validate([
            'kurir' => 'required|in:jne,jnt,parcel,ninja express',
            'no_resi' => 'required|string|max:255',
        ]);

        $pesanan = Pesanan::where('id_pesanan', $id)->firstOrFail();

        \DB::transaction(function () use ($pesanan, $request) {
            $pesanan->update([
                'status_pesanan' => 'Pesanan dalam pengiriman',
            ]);

            \App\Models\RiwayatPesanan::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'status' => 'Dikirim',
                'deskripsi' => 'Paket sedang dalam perjalanan oleh kurir ' . strtoupper($request->kurir) . ' dengan nomor resi ' . $request->no_resi . '.'
            ]);

            \App\Models\Pengiriman::updateOrCreate(
                ['id_pesanan' => $pesanan->id_pesanan],
                [
                    'id_pengiriman' => 'KRM' . strtoupper(\Illuminate\Support\Str::random(8)),
                    'kurir' => $request->kurir,
                    'no_resi' => $request->no_resi,
                    'alamat_tujuan' => $pesanan->alamat ?? 'Alamat tidak tersedia',
                    'tanggal_pengiriman' => now(),
                    'estimasi_tiba' => now()->addDays(3),
                    'status_pengiriman' => 'dikirim',
                ]
            );
        });

        // Kirim notifikasi ke pembeli
        NotifikasiUser::create([
            'id_user' => $pesanan->id_user,
            'judul' => 'Pesanan Dikirim',
            'pesan' => 'Pesanan #' . $pesanan->kode_invoice . ' telah dikirim menggunakan ' . strtoupper($request->kurir) . ' dengan resi ' . $request->no_resi . '.',
            'type' => 'success',
        ]);

        return redirect()->route('penjual.pesanan.index', ['status' => 'dikirim'])->with('success', 'Resi berhasil diinput dan pesanan dikirim.');
    }

    /**
     * Tolak pesanan
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string',
        ]);

        $pesanan = Pesanan::where('id_pesanan', $id)->firstOrFail();

        $pesanan->update([
            'status_pesanan' => 'Pesanan ditolak',
            'catatan' => $request->alasan,
        ]);

        \App\Models\RiwayatPesanan::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'status' => 'Dibatalkan',
            'deskripsi' => 'Pesanan ditolak oleh penjual dengan alasan: ' . $request->alasan
        ]);

        // Kirim notifikasi ke pembeli
        NotifikasiUser::create([
            'id_user' => $pesanan->id_user,
            'judul' => 'Pesanan Ditolak',
            'pesan' => $request->alasan,
            'type' => 'danger',
        ]);

        return redirect()->route('penjual.pesanan.index', ['status' => 'dibatalkan'])->with('success', 'Pesanan berhasil ditolak');
    }
}
