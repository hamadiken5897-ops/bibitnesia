@extends('layouts.penjual.penjual')

@section('content')

<div class="container-fluid">

    <h3 class="fw-bold">Dashboard Penjual</h3>
    <p class="text-muted">Selamat datang di panel penjual</p>

    {{-- STATISTIK --}}
    <div class="row mt-4">

        <div class="col-md-3">
            <div class="p-4 bg-white rounded shadow-sm">
                <h4 class="fw-bold">120</h4>
                <p class="text-muted">Produk Terjual</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-4 bg-white rounded shadow-sm">
                <h4 class="fw-bold">45</h4>
                <p class="text-muted">Pesanan Pending</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="p-4 bg-white rounded shadow-sm">
                <h4 class="fw-bold">Rp 12.500.000</h4>
                <p class="text-muted">Pendapatan Bulan Ini</p>
            </div>
        </div>

    </div>

    {{-- GRAFIK --}}
    <div class="card mt-4">
        <div class="card-header">
            <h4>Grafik Penjualan Bulanan</h4>
        </div>
        <div class="card-body">
            <canvas id="penjualanChart" height="120"></canvas>
        </div>
    </div>

</div>

@endsection


@push('scripts')
<script>
    const ctx = document.getElementById('penjualanChart').getContext('2d');

    // Gradasi warna hijau
    const gradient = ctx.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(13, 167, 96, 0.35)');
    gradient.addColorStop(1, 'rgba(13, 167, 96, 0)');

    const data = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        datasets: [{
            label: 'Penjualan',
            data: [12, 17, 15, 21, 18, 24, 20, 28, 22, 31, 27, 34],
            borderColor: '#0da760',
            backgroundColor: gradient,
            borderWidth: 3,
            tension: 0.35,
            fill: true,
            pointRadius: 4,
            pointBackgroundColor: '#0da760',
            pointHoverRadius: 6,
        }]
    };

    const config = {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#666',
                        font: { size: 11 }
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },
                },
                y: {
                    ticks: {
                        color: '#999',
                        font: { size: 11 }
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.04)'
                    },
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            }
        },
    };

    const penjualanChart = new Chart(ctx, config);
</script>
@endpush
