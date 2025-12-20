<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualController extends Controller
{
    // 📊 DATA CHART (KHUSUS PESANAN PENJUAL)
    $chartDataRaw = DB::table('pesanans')
    ->join('detail_pesanans', 'pesanans.id_pesanan', '=', 'detail_pesanans.id_pesanan')
    ->join('produks', 'detail_pesanans.id_produk', '=', 'produks.id_produk')
    ->where('produks.id_penjual', auth()->user()->id_user)
    ->select(DB::raw('MONTH(pesanans.created_at) as bulan'), DB::raw('COUNT(DISTINCT pesanans.id_pesanan) as total'))
    ->groupBy('bulan')
    ->get();

$chartLabels = $chartDataRaw->pluck('bulan');
$chartData = $chartDataRaw->pluck('total');

// 📦 DATA PESANAN UNTUK TABEL - FIXED
$pesanan = DB::table('pesanans')
    ->join('detail_pesanans', 'pesanans.id_pesanan', '=', 'detail_pesanans.id_pesanan')
    ->join('produks', 'detail_pesanans.id_produk', '=', 'produks.id_produk')
    ->join('users', 'pesanans.id_user', '=', 'users.id_user')
    ->where('produks.id_penjual', auth()->user()->id_user)
    ->select(
        'pesanans.id_pesanan',
        'pesanans.id_user',
        'pesanans.total_harga',
        'pesanans.status',
        'pesanans.created_at',
        'pesanans.updated_at',
        'users.nama as nama_pembeli'
    )
    ->groupBy(
        'pesanans.id_pesanan',
        'pesanans.id_user',
        'pesanans.total_harga',
        'pesanans.status',
        'pesanans.created_at',
        'pesanans.updated_at',
        'users.nama'
    )
    ->orderBy('pesanans.created_at', 'desc')
    ->get();

return view('penjual.penjual', compact('chartLabels', 'chartData', 'pesanan'));
}
}
