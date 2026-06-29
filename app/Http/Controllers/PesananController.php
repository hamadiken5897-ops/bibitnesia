<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\LaporanPenjual;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'semua');
        
        $query = Pesanan::with(['detailPesanan.produk', 'pengiriman'])
            ->where('id_user', auth()->user()->id_user)
            ->orderBy('created_at', 'desc');

        if ($status != 'semua') {
            if ($status == 'belum-bayar') {
                $query->where('status_pesanan', 'Menunggu Pembayaran');
            } elseif ($status == 'menunggu-konfirmasi') {
                $query->where('status_pesanan', 'Menunggu konfirmasi penjual');
            } elseif ($status == 'dikemas') {
                $query->where('status_pesanan', 'Pesanan sedang diproses');
            } elseif ($status == 'dikirim') {
                $query->whereIn('status_pesanan', ['Pesanan dalam pengiriman', 'Sampai Tujuan']);
            } elseif ($status == 'selesai') {
                $query->where('status_pesanan', 'Pesanan selesai');
            } elseif ($status == 'dibatalkan') {
                $query->where('status_pesanan', 'Pesanan ditolak');
            }
        }

        $pesanan = $query->get();

        return view('marketplace.pesanan', compact('pesanan', 'status'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk', 'pengiriman', 'riwayat'])
            ->where('id_user', auth()->user()->id_user)
            ->where('id_pesanan', $id)
            ->firstOrFail();

        return view('marketplace.detail-pesanan', compact('pesanan'));
    }
    public function selesai($id)
    {
        $pesanan = Pesanan::with('detailPesanan.produk')->findOrFail($id);

        if ($pesanan->status_pesanan !== 'Pesanan selesai' && $pesanan->status_pesanan !== 'Pesanan Selesai') {
            
            DB::transaction(function () use ($pesanan) {
                // Cek agar tidak double entry jika kurir sudah menyelesaikannya
                $sudahDicatat = LaporanPenjual::where('id_pesanan', $pesanan->id_pesanan)->exists();
                
                if (!$sudahDicatat) {
                    // Ambil pengaturan biaya layanan (Default 5%)
                    $pengaturan = \App\Models\PengaturanPembayaran::first();
                    $komisiPersen = $pengaturan ? $pengaturan->biaya_layanan_persen : 5.00;
                    $komisiMultiplier = $komisiPersen / 100;

                    // 1. Bagi pendapatan barang ke masing-masing penjual
                    foreach ($pesanan->detailPesanan as $detail) {
                        if ($detail->produk && $detail->produk->id_penjual) {
                            $pendapatanKotor = $detail->harga_satuan * $detail->jumlah;
                            $komisi = $pendapatanKotor * $komisiMultiplier;
                            $pendapatanBersih = $pendapatanKotor - $komisi;
                            
                            // catat pemasukan per penjual
                            LaporanPenjual::create([
                                'id_penjual' => $detail->produk->id_penjual,
                                'id_pesanan' => $pesanan->id_pesanan,
                                'jumlah' => $pendapatanBersih,
                                'komisi' => $komisi,
                            ]);
                            
                            // Tambahkan uang bersih ke saldo dompet digital penjual
                            \App\Models\Penjual::where('id_penjual', $detail->produk->id_penjual)
                                ->increment('saldo', $pendapatanBersih);
                        }
                    }

                    // 2. Bagi pendapatan ongkir ke Kurir
                    if ($pesanan->pengiriman && $pesanan->pengiriman->id_kurir) {
                        // Ambil ongkir dari detail_pesanans pertama (karena ongkir flat per pesanan)
                        $ongkir = $pesanan->detailPesanan->first()->ongkir ?? 0;

                        if ($ongkir > 0) {
                            \App\Models\LaporanKurir::create([
                                'id_kurir' => $pesanan->pengiriman->id_kurir,
                                'id_pesanan' => $pesanan->id_pesanan,
                                'jumlah' => $ongkir,
                            ]);

                            \App\Models\Kurir::where('id_kurir', $pesanan->pengiriman->id_kurir)
                                ->increment('saldo', $ongkir);
                        }
                    }
                }

                $pesanan->update(['status_pesanan' => 'Pesanan Selesai']);

                \App\Models\RiwayatPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'status' => 'Pesanan Selesai',
                    'deskripsi' => 'Pesanan telah diselesaikan oleh pembeli. Dana akan diteruskan ke penjual.'
                ]);
            });
        }

        return back()->with('success', 'Pesanan selesai & pemasukan dicatat');
    }
}
