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
    public function index()
    {
        // Ambil pesanan yang statusnya "sedang diproses"
        $pesanan = Pesanan::with(['user', 'detailPesanan.produk'])
            ->where('status_pesanan', 'Pesanan sedang diproses')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('penjual.pesanan.index', compact('pesanan'));
    }

    /**
     * Menampilkan detail pesanan
     */
    public function show($id)
    {
        $pesanan = Pesanan::with(['user', 'detailPesanan.produk'])
            ->where('id_pesanan', $id)
            ->firstOrFail();

        // Cek apakah ada detail pesanan
        if ($pesanan->detailPesanan && $pesanan->detailPesanan->isNotEmpty()) {
            // Opsional: Cek apakah pesanan ini punya produk milik penjual yang login
            $hasOwnProduct = $pesanan->detailPesanan->contains(function ($detail) {
                return $detail->produk && $detail->produk->id_user === auth()->id();
            });

            // Uncomment jika ingin restrict akses hanya ke pesanan sendiri
            // if (!$hasOwnProduct) {
            //     abort(403, 'Unauthorized access to this order');
            // }
        }

        return view('penjual.pesanan.show', compact('pesanan'));
    }
    /**
     * Terima pesanan
     */
    public function accept($id)
    {
        $pesanan = Pesanan::where('id_pesanan', $id)->firstOrFail();

        $pesanan->update([
            'status_pesanan' => 'Pesanan dalam pengiriman',
        ]);

        // Kirim notifikasi ke pembeli
        NotifikasiUser::create([
            'id_user' => $pesanan->id_user,
            'judul' => 'Pesanan Diterima',
            'pesan' => 'Pesanan #' . $pesanan->kode_invoice . ' telah diterima penjual dan sedang diproses.',
            'type' => 'success',
        ]);

        return redirect()->route('penjual.pesanan.index')->with('success', 'Pesanan berhasil diterima!');
    }

    /**
     * Tolak pesanan
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|min:10',
        ]);

        $pesanan = Pesanan::where('id_pesanan', $id)->firstOrFail();

        $pesanan->update([
            'status_pesanan' => 'Pesanan ditolak',
            'catatan' => $request->alasan,
        ]);

        // Kirim notifikasi ke pembeli
        NotifikasiUser::create([
            'id_user' => $pesanan->id_user,
            'judul' => 'Pesanan Ditolak',
            'pesan' => $request->alasan,
            'type' => 'danger',
        ]);

        return redirect()->route('penjual.pesanan.index')->with('success', 'Pesanan berhasil ditolak');
    }
}
