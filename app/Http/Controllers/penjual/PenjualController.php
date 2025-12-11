<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualController extends Controller
{
    public function index()
    {
        // Ambil data dari database (contoh: tabel pesanan per bulan)
        $data = DB::table('pesanans')
            ->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('COUNT(*) as total'))
            ->groupBy('bulan')
            ->get();

        // Label bulan
        $chartLabels = $data->pluck('bulan');
        // Data total
        $chartData = $data->pluck('total');

        return view('penjual.penjual', [
            'chartLabels' => $chartLabels,
            'chartData' => $chartData
        ]);
    }
}
