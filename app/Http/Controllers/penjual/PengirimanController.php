<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PengirimanController extends Controller
{
    // form pilih kurir
    public function create($idPesanan)
    {
        $pesanan = Pesanan::findOrFail($idPesanan);
        return view('penjual.pengiriman.create', compact('pesanan'));
    }

    // simpan pengiriman
    public function store(Request $request)
    {
        $request->validate([
            'id_pesanan' => 'required',
            'kurir' => 'required',
        ]);

        $pesanan = Pesanan::findOrFail($request->id_pesanan);

        Pengiriman::create([
            'id_pengiriman' => 'SHIP-' . strtoupper(Str::random(10)),
            'id_pesanan' => $pesanan->id_pesanan,
            'kurir' => $request->kurir,

            // SNAPSHOT DARI PESANAN
            'alamat_tujuan' =>
                $pesanan->alamat . ', ' .
                optional($pesanan->provinsiRelasi)->nama_provinsi,

            'status_pengiriman' => 'dikemas',
            'tanggal_pengiriman' => now(),
        ]);

        $pesanan->update([
            'status_pesanan' => 'menunggu_kurir',
        ]);

        return redirect()
            ->route('penjual.pesanan.index')
            ->with('success', 'Pengiriman berhasil dibuat');
    }
}
