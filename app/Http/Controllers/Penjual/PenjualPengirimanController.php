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
                'status_pengiriman' => 'diproses',
            ]);

            $pesanan->update([
                'status_pesanan' => 'Pesanan dalam pengiriman',
            ]);
        });

        return redirect()->route('penjual.pesanan.index', ['status' => 'dikirim'])->with('success', 'Kurir berhasil ditugaskan');
    }
}
