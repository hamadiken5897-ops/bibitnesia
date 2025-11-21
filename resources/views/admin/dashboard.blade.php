@extends('layouts.admin')

@section('title', 'Dashboard - BibitNesia Admin')
@section('page-title', 'Dashboard Overview')

@section('content')

    {{-- ✅ Tambahkan CSS Shadow di sini --}}
    <style>
        .card {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
        }
    </style>

    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon purple">
                                        <i class="iconly-boldShow"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Kunjungan</h6>
                                    <h6 class="font-extrabold mb-0">112.000</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon blue">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Pengguna</h6>
                                    <h6 class="font-extrabold mb-0">183.000</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon green">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Penjual</h6>
                                    <h6 class="font-extrabold mb-0">500</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon red">
                                        <i class="iconly-boldBookmark"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Produk</h6>
                                    <h6 class="font-extrabold mb-0">112</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chart Section --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Kunjungan Pengguna</h4>
                        </div>
                        <div class="card-body">
                            <div id="chart-profile-visit"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Sidebar Section --}}
        <div class="col-12 col-lg-3">
            {{-- Card Profil User Login --}}
            <div class="card">
                <div class="card-body py-4 px-5">
                    <div class="d-flex align-items-center">
                        @if (auth()->user()->file)
                            <img src="{{ auth()->user()->file->file_stream }}" alt="{{ auth()->user()->nama }}"
                                class="rounded-circle border" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama) }}&size=80&background=random"
                                alt="{{ auth()->user()->nama }}" class="rounded-circle"
                                class="rounded-circle border" style="width: 80px; height: 80px; object-fit: cover;">
                        @endif

                        <div class="ms-3 name">
                            <h5 class="font-bold">{{ auth()->user()->nama }}</h5>
                            <h6 class="text-muted mb-0">
                                {{ auth()->user()->admin->jabatan_alias ?? 'Staff' }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Recent Messages (Opsional: Bisa ditampilkan semua admin) --}}
            <div class="card">
                <div class="card-header">
                    <h4>Tim Admin</h4>
                </div>
                <div class="card-content pb-4">

                    @forelse($admins ?? [] as $admin)
                        <div class="recent-message d-flex px-4 py-3">
                            <div class="avatar avatar-lg">
                                @if ($admin->user->file)
                                    <img src="{{ $admin->user->file->file_stream }}" alt="{{ $admin->user->nama }}"
                                        class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->user->nama) }}&size=60&background=random"
                                        alt="{{ $admin->user->nama }}" class="rounded-circle">
                                @endif
                            </div>
                            <div class="name ms-4">
                                <h5 class="mb-1">{{ $admin->user->nama }}</h5>
                                <h6 class="text-muted mb-0">{{ $admin->jabatan_alias }}</h6>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-3 text-center text-muted">
                            <small>Belum ada admin lain</small>
                        </div>
                    @endforelse

                    <div class="px-4">
                        <button class='btn btn-block btn-xl btn-light-primary font-bold mt-3'>
                            Lihat Semua Admin
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-9">
            {{-- Regional Chart Section --}}
            <div class="row">
                <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Kunjungan Pengguna</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-primary" width="32" height="32" fill="blue"
                                            style="width:10px">
                                            <use
                                                xlink:href="{{ asset('dist/assets/vendors/bootstrap-icons/bootstrap-icons.svg#circle-fill') }}" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">Sumatera</h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h5 class="mb-0">862</h5>
                                </div>
                                <div class="col-12">
                                    <div id="chart-europe"></div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-7">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-success" width="32" height="32" fill="blue"
                                            style="width:10px">
                                            <use
                                                xlink:href="{{ asset('dist/assets/vendors/bootstrap-icons/bootstrap-icons.svg#circle-fill') }}" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">Kalimantan</h5>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <h5 class="mb-0">375</h5>
                                </div>
                                <div class="col-12">
                                    <div id="chart-america"></div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-danger" width="32" height="32" fill="blue"
                                            style="width:10px">
                                            <use
                                                xlink:href="{{ asset('dist/assets/vendors/bootstrap-icons/bootstrap-icons.svg#circle-fill') }}" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">Jawa</h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h5 class="mb-0">1025</h5>
                                </div>
                                <div class="col-12">
                                    <div id="chart-indonesia"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Chart Keuangan --}}
                <div class="col-12 col-xl-8">
                    <div class="card finance-card">
                        <div class="card-header border-0">
                            <h4 class="chart-title">Grafik Keuangan</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="financeChart" height="220"></canvas>

                            <div class="chart-footer mt-4 text-center">
                                <p class="mb-1 text-muted" style="font-size: 13px;">Pendapatan per Bulan</p>
                                <h5 id="incomeValue" class="fw-semibold text-success">Rp 17.500.000</h5>
                                <p class="text-secondary" style="font-size: 12px;">Januari - Desember 2025</p>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    /* 🌿 Clean Finance Card */
                    .finance-card {
                        background: #ffffff;
                        border: 1px solid rgba(0, 0, 0, 0.08);
                        border-radius: 14px;
                        padding: 15px;
                        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
                        transition: all 0.3s ease;
                    }

                    .finance-card:hover {
                        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.12);
                        transform: translateY(-3px);
                    }

                    .chart-title {
                        color: #0a8a50;
                        font-size: 1.1rem;
                        font-weight: 600;
                        letter-spacing: 0.3px;
                        margin-bottom: 0;
                    }

                    #financeChart {
                        width: 100%;
                        border-radius: 8px;
                    }

                    .chart-footer {
                        border-top: 1px solid rgba(0, 0, 0, 0.06);
                        padding-top: 12px;
                    }

                    #incomeValue {
                        color: #0da760;
                    }
                </style>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const ctx = document.getElementById('financeChart').getContext('2d');

                    // Gradasi hijau lembut untuk area grafik
                    const gradient = ctx.createLinearGradient(0, 0, 0, 250);
                    gradient.addColorStop(0, 'rgba(13, 167, 96, 0.35)');
                    gradient.addColorStop(1, 'rgba(13, 167, 96, 0)');

                    const data = {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        datasets: [{
                            label: 'Pendapatan',
                            data: [12, 17, 15, 21, 18, 24, 20, 28, 22, 31, 27, 34],
                            borderColor: '#0da760',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            tension: 0.35, // 💡 Melengkung halus dan jelas
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
                                        font: {
                                            size: 11
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(0,0,0,0.05)'
                                    },
                                },
                                y: {
                                    ticks: {
                                        color: '#999',
                                        font: {
                                            size: 11
                                        }
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

                    const financeChart = new Chart(ctx, config);

                    // Update angka pendapatan secara dinamis
                    setInterval(() => {
                        const random = 15000000 + Math.random() * 8000000;
                        document.getElementById('incomeValue').textContent =
                            "Rp " + new Intl.NumberFormat('id-ID').format(random.toFixed(0));

                        data.datasets[0].data.shift();
                        data.datasets[0].data.push(10 + Math.random() * 25);
                        financeChart.update();
                    }, 1800);
                </script>

            </div>
        </div>




        </div>
    </section>
@endsection
