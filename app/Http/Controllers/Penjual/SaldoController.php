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

        $laporan = LaporanPenjual::select('id_pesanan', DB::raw('SUM(jumlah) as total_jumlah'), DB::raw('MAX(created_at) as tgl_masuk'))
            ->where('id_penjual', $id_penjual)
            ->groupBy('id_pesanan')
            ->orderBy('tgl_masuk', 'desc')
            ->paginate(10);

        $pesananIds = $laporan->pluck('id_pesanan');
        $pesananDetails = Pesanan::with(['detailPesanan.produk' => function($q) use ($id_penjual) {
            $q->where('id_penjual', $id_penjual);
        }])->whereIn('id_pesanan', $pesananIds)->get()->keyBy('id_pesanan');

        return view('penjual.saldo.index', compact(
            'total_pemasukan',
            'total_pesanan',
            'laporan',
            'pesananDetails'
        ));
    }
}
