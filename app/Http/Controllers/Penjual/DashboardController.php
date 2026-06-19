<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\LaporanPenjual;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $penjual = auth()->user()->penjual;

        if (!$penjual) {
            abort(403, 'Anda bukan penjual');
        }

        $id_penjual = $penjual->id_penjual;

        // 1. Produk Terjual (SUM jumlah di detail_pesanans untuk pesanan selesai)
        $produkTerjual = DetailPesanan::whereHas('produk', function ($q) use ($id_penjual) {
            $q->where('id_penjual', $id_penjual);
        })->whereHas('pesanan', function ($q) {
            $q->whereIn('status_pesanan', ['Pesanan Selesai']);
        })->sum('jumlah');

        // 2. Pesanan Pending (Pesanan aktif: Menunggu Pembayaran, Pesanan diproses, Pesanan dalam pengiriman)
        $pesananPending = Pesanan::whereHas('detailPesanan.produk', function ($q) use ($id_penjual) {
            $q->where('id_penjual', $id_penjual);
        })->where('status_pesanan', '!=', 'Pesanan Selesai')->count();

        // 3. Pendapatan Bulan Ini
        $awalBulan = Carbon::now()->startOfMonth();
        $akhirBulan = Carbon::now()->endOfMonth();
        $pendapatanBulanIni = LaporanPenjual::where('id_penjual', $id_penjual)
            ->whereBetween('created_at', [$awalBulan, $akhirBulan])
            ->sum('jumlah');

        // 4. Total Kunjungan Produk
        $totalKunjungan = Produk::where('id_penjual', $id_penjual)->sum('jumlah_lihat');

        // 5. Data Grafik Penjualan (30 hari terakhir)
        $tigaPuluhHariLalu = Carbon::now()->subDays(29)->startOfDay();
        $penjualanHarian = LaporanPenjual::where('id_penjual', $id_penjual)
            ->where('created_at', '>=', $tigaPuluhHariLalu)
            ->select(DB::raw('DATE(created_at) as tanggal'), DB::raw('SUM(jumlah) as total'))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get()
            ->keyBy('tanggal');

        $grafikLabel = [];
        $grafikData = [];
        for ($i = 29; $i >= 0; $i--) {
            $tgl = Carbon::now()->subDays($i);
            $tanggalFormat = $tgl->format('Y-m-d');
            $grafikLabel[] = $tgl->format('d M');
            $grafikData[] = isset($penjualanHarian[$tanggalFormat]) ? $penjualanHarian[$tanggalFormat]->total : 0;
        }

        // 6. Data Histogram Kunjungan (Top 5 Produk)
        $topProduk = Produk::where('id_penjual', $id_penjual)
            ->orderBy('jumlah_lihat', 'desc')
            ->limit(5)
            ->get();
            
        $topProdukLabel = $topProduk->pluck('nama_produk')->map(function($name) {
            return strlen($name) > 15 ? substr($name, 0, 15) . '...' : $name;
        })->toArray();
        $topProdukData = $topProduk->pluck('jumlah_lihat')->toArray();

        return view('penjual.penjual', compact(
            'produkTerjual',
            'pesananPending',
            'pendapatanBulanIni',
            'totalKunjungan',
            'grafikLabel',
            'grafikData',
            'topProdukLabel',
            'topProdukData'
        ));
    }
}
