<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use App\Models\Penjual;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 📊 Statistik Cards
        $totalKunjungan = $this->getTotalVisits();
        $totalPengguna = User::where('role', '!=', 'admin')->count();
        $totalPenjual = Penjual::count();
        $totalProduk = Produk::count();

        // 👥 Data Admin untuk sidebar (kecuali yang sedang login)
        $admins = Admin::with('user.file')
            ->whereHas('user', function($query) {
                $query->where('id_user', '!=', auth()->id());
            })
            ->limit(5)
            ->get();

        // 📈 Data Chart Kunjungan per Bulan (12 bulan terakhir)
        $chartKunjungan = $this->getMonthlyVisits();

        // 🗺️ Data Kunjungan per Regional
        $kunjunganRegional = $this->getRegionalVisits();

        // 💰 Data Keuangan per Bulan
        $chartKeuangan = $this->getMonthlyRevenue();

        return view('admin.dashboard', compact(
            'totalKunjungan',
            'totalPengguna',
            'totalPenjual',
            'totalProduk',
            'admins',
            'chartKunjungan',
            'kunjunganRegional',
            'chartKeuangan'
        ));
    }

    /**
     * Get total visits
     */
    private function getTotalVisits()
    {
        // Gunakan jumlah produk * angka random sebagai estimasi kunjungan
        // Atau bisa pakai count user * faktor tertentu
        $totalProduk = Produk::count();
        return $totalProduk * 150; // Estimasi: setiap produk dilihat ~150x
    }

    /**
     * Get monthly visits data untuk chart (12 bulan terakhir)
     */
    private function getMonthlyVisits()
    {
        // Dummy data untuk chart (karena tidak ada kolom created_at di users)
        // Bisa diganti dengan data real dari tabel tracking visits nanti
        $monthlyData = [];
        $baseValue = User::count(); // Ambil total user sebagai basis
        
        for ($i = 1; $i <= 12; $i++) {
            // Generate data yang naik secara bertahap (simulasi pertumbuhan)
            $monthlyData[] = (int) ($baseValue * 0.05 * $i) + rand(10, 50);
        }

        return $monthlyData;
    }

    /**
     * Get regional visits based on seller's province
     */
    private function getRegionalVisits()
    {
        $regionalMapping = [
            'Sumatera' => ['Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Jambi', 'Sumatera Selatan', 'Bengkulu', 'Lampung', 'Kepulauan Bangka Belitung', 'Kepulauan Riau'],
            'Jawa' => ['DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Banten'],
            'Kalimantan' => ['Kalimantan Barat', 'Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara'],
        ];

        $penjualPerProvinsi = Penjual::with('provinsi')
            ->get()
            ->groupBy('provinsi.nama_provinsi');

        $regional = [
            'Sumatera' => 0,
            'Jawa' => 0,
            'Kalimantan' => 0,
        ];

        foreach ($penjualPerProvinsi as $provinsi => $penjuals) {
            foreach ($regionalMapping as $region => $provinces) {
                if (in_array($provinsi, $provinces)) {
                    $regional[$region] += $penjuals->count();
                    break;
                }
            }
        }

        return $regional;
    }

    /**
     * Get monthly revenue (dummy data untuk testing)
     */
    private function getMonthlyRevenue()
    {
        $revenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenue[] = rand(10000000, 35000000);
        }

        return $revenue;
    }
}