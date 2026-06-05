@extends('layouts.penjual.penjual')

@section('content')

<div class="container-fluid">

    <h3 class="fw-bold">Dashboard Penjual</h3>
    <p class="text-muted">Selamat datang di panel penjual</p>

    {{-- STATISTIK --}}
    <div class="row mt-4">

        <div class="col-md-3 mb-4">
            <a href="{{ route('penjual.produk') }}" class="text-decoration-none text-dark">
                <div class="p-4 bg-white rounded shadow-sm h-100 hover-card">
                    <h4 class="fw-bold text-primary">{{ $produkTerjual ?? 0 }}</h4>
                    <p class="text-muted mb-0">Produk Terjual</p>
                </div>
            </a>
        </div>

        <div class="col-md-3 mb-4">
            <a href="{{ route('penjual.pesanan.index') }}" class="text-decoration-none text-dark">
                <div class="p-4 bg-white rounded shadow-sm h-100 hover-card">
                    <h4 class="fw-bold text-warning">{{ $pesananPending ?? 0 }}</h4>
                    <p class="text-muted mb-0">Pesanan Pending</p>
                </div>
            </a>
        </div>

        <div class="col-md-3 mb-4">
            <a href="{{ route('penjual.saldo') }}" class="text-decoration-none text-dark">
                <div class="p-4 bg-white rounded shadow-sm h-100 hover-card">
                    <h4 class="fw-bold text-success">Rp {{ number_format($pendapatanBulanIni ?? 0, 0, ',', '.') }}</h4>
                    <p class="text-muted mb-0">Pendapatan Bulan Ini</p>
                </div>
            </a>
        </div>

        <div class="col-md-3 mb-4">
            <a href="{{ route('penjual.produk') }}" class="text-decoration-none text-dark">
                <div class="p-4 bg-white rounded shadow-sm h-100 hover-card">
                    <h4 class="fw-bold text-info">{{ $totalKunjungan ?? 0 }}</h4>
                    <p class="text-muted mb-0">Kunjungan Produk</p>
                </div>
            </a>
        </div>

    </div>

    {{-- GRAFIK --}}
    <div class="row mt-2">
        <div class="col-lg-7 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold text-muted">Grafik Penjualan 30 Hari Terakhir</h5>
                </div>
                <div class="card-body">
                    <canvas id="penjualanChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold text-muted">Top 5 Produk (Kunjungan)</h5>
                </div>
                <div class="card-body">
                    <canvas id="kunjunganChart" height="210"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>

@endsection


@push('scripts')
<script>
    // --- 1. Grafik Penjualan (Line Chart) ---
    const ctxPenjualan = document.getElementById('penjualanChart').getContext('2d');
    
    // Gradasi warna hijau
    const gradient = ctxPenjualan.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(13, 167, 96, 0.35)');
    gradient.addColorStop(1, 'rgba(13, 167, 96, 0)');

    const dataPenjualan = {
        labels: @json($grafikLabel ?? []),
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: @json($grafikData ?? []),
            borderColor: '#0da760',
            backgroundColor: gradient,
            borderWidth: 3,
            tension: 0.35,
            fill: true,
            pointRadius: 2,
            pointBackgroundColor: '#0da760',
            pointHoverRadius: 6,
        }]
    };

    new Chart(ctxPenjualan, {
        type: 'line',
        data: dataPenjualan,
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    ticks: { color: '#888', font: { size: 10 } },
                    grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                },
                y: {
                    ticks: { 
                        color: '#888', font: { size: 10 },
                        callback: function(value) { return 'Rp ' + (value/1000) + 'k'; }
                    },
                    grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                    beginAtZero: true
                }
            },
            animation: { duration: 1000, easing: 'easeInOutQuart' }
        },
    });

    // --- 2. Grafik Kunjungan (Bar Chart) ---
    const ctxKunjungan = document.getElementById('kunjunganChart').getContext('2d');
    
    new Chart(ctxKunjungan, {
        type: 'bar',
        data: {
            labels: @json($topProdukLabel ?? []),
            datasets: [{
                label: 'Views',
                data: @json($topProdukData ?? []),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, color: '#888' },
                    grid: { color: 'rgba(0,0,0,0.03)' }
                },
                x: {
                    ticks: { color: '#888', font: { size: 10 } },
                    grid: { display: false }
                }
            },
            animation: { duration: 1200, easing: 'easeOutBounce' }
        }
    });
</script>
@endpush
