<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    public function index()
    {
        $id_penjual = auth()->user()->id_user;

        $total_pemasukan = LaporanPenjual::where('id_penjual', $id_penjual)->sum('jumlah');
        $total_pesanan   = LaporanPenjual::where('id_penjual', $id_penjual)->count();

        $laporan = LaporanPenjual::where('id_penjual', $id_penjual)
            ->latest()
            ->get();

        return view('penjual.saldo.index', compact(
            'total_pemasukan',
            'total_pesanan',
            'laporan'
        ));
    }
}
