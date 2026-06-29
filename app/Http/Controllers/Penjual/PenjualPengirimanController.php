<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Pesanan;
use App\Models\Kurir;
use App\Models\Provinsi;
use App\Models\Pengiriman;
use Illuminate\Support\Str;

class PenjualPengirimanController extends Controller
{
    public function index(Request $request)
    {
        $id_penjual = auth()->user()->penjual->id_penjual;
        $query = Pengiriman::with(['pesanan.user', 'kurir.user'])
                           ->whereHas('pesanan.detailPesanan.produk', function ($q) use ($id_penjual) {
                               $q->where('id_penjual', $id_penjual);
                           });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_resi', 'like', "%{$search}%")
                  ->orWhere('id_pengiriman', 'like', "%{$search}%")
                  ->orWhereHas('pesanan.user', function($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status_pengiriman', $request->status);
        }

        $pengiriman = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('penjual.pengiriman.index', compact('pengiriman'));
    }

    public function show($id)
    {
        $id_penjual = auth()->user()->penjual->id_penjual;
        $pengiriman = Pengiriman::with(['pesanan.user', 'kurir.user', 'pesanan.detailPesanan.produk', 'pesanan.riwayat'])
            ->whereHas('pesanan.detailPesanan.produk', function ($q) use ($id_penjual) {
                $q->where('id_penjual', $id_penjual);
            })
            ->where('id_pengiriman', $id)
            ->firstOrFail();

        return view('penjual.pengiriman.show', compact('pengiriman'));
    }

    public function pilihKurir(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $query = Kurir::with('user')->where('status_kurir', 'AKTIF');

        // 🔎 search nama
        if ($request->filled('q')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%');
            });
        }

        // 📍 filter provinsi
        if ($request->filled('provinsi')) {
            $query->where('id_provinsi', $request->provinsi);
        }

        $kurir = $query->get();
        $provinsi = Provinsi::orderBy('nama_provinsi')->get();

        return view('penjual.status-pesanan.pilih-kurir', compact('pesanan', 'kurir', 'provinsi'));
    }

    public function simpanKurir(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        DB::transaction(function () use ($request, $pesanan) {
            Pengiriman::create([
                'id_pengiriman' => 'SHIP-' . Str::upper(Str::random(10)),
                'id_pesanan' => $pesanan->id_pesanan,
                'id_kurir' => $request->id_kurir,
                'alamat_tujuan' => $pesanan->alamat,
                'status_pengiriman' => 'menunggu_kurir',
            ]);

            $pesanan->update([
                'status_pesanan' => 'Menunggu penjemputan kurir',
            ]);

            \App\Models\RiwayatPesanan::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'status' => 'Menunggu Penjemputan',
                'deskripsi' => 'Penjual telah menyerahkan tugas penjemputan kepada kurir. Menunggu kurir mengambil paket.'
            ]);
        });

        return redirect()->route('penjual.pesanan.index', ['status' => 'dikirim'])->with('success', 'Kurir berhasil ditugaskan dan menunggu penjemputan');
    }
}
