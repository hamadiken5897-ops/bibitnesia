@extends('layouts.admin.admin')

@section('title', 'Dashboard - BibitNesia Admin')
@section('page-title', 'Dashboard Overview')

@section('content')

    {{-- CSS Shadow --}}
    <style>
        .chart-container {
            position: relative;
            height: 280px;
            /* ← atur sesuai selera */
            width: 180%;
        }

        .card {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
        }

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

    <section class="row">
        <div class="col-12 col-lg-9">
            {{-- Statistics Cards --}}
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
                                    <h6 class="font-extrabold mb-0">{{ number_format($totalKunjungan) }}</h6>
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
                                    <h6 class="font-extrabold mb-0">{{ number_format($totalPengguna) }}</h6>
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
                                    <h6 class="font-extrabold mb-0">{{ number_format($totalPenjual) }}</h6>
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
                                    <h6 class="font-extrabold mb-0">{{ number_format($totalProduk) }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chart Kunjungan --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Kunjungan Pengguna</h4>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="chart-profile-visit"></canvas>
                                </div>
                            </div>
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
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->nama) }}&size=80&background=27ae60&color=fff"
                                alt="{{ auth()->user()->nama }}" class="rounded-circle border"
                                style="width: 80px; height: 80px; object-fit: cover;">
                        @endif

                        <div class="ms-3 name">
                            <h5 class="font-bold">{{ auth()->user()->nama }}</h5>
                            <h6 class="text-muted mb-0">
                                {{ auth()->user()->admin->jabatan_alias ?? 'Administrator' }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Tim Admin --}}
            <div class="card">
                <div class="card-header">
                    <h4>Tim Admin</h4>
                </div>
                <div class="card-content pb-4">
                    @forelse($admins as $admin)
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
                            <h4>Kunjungan Regional</h4>
                        </div>
                        <div class="card-body">
                            @foreach (['Sumatera' => 'blue', 'Kalimantan' => 'success', 'Jawa' => 'danger'] as $region => $color)
                                <div class="row {{ $loop->first ? '' : 'mt-3' }}">
                                    <div class="col-7">
                                        <div class="d-flex align-items-center">
                                            <svg class="bi text-{{ $color }}" width="32" height="32"
                                                fill="blue" style="width:10px">
                                                <use
                                                    xlink:href="{{ asset('dist/assets/vendors/bootstrap-icons/bootstrap-icons.svg#circle-fill') }}" />
                                            </svg>
                                            <h5 class="mb-0 ms-3">{{ $region }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <h5 class="mb-0">{{ $kunjunganRegional[$region] ?? 0 }}</h5>
                                    </div>
                                    <div class="col-12">
                                        <div id="chart-{{ strtolower($region) }}"></div>
                                    </div>
                                </div>
                            @endforeach
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
                                <h5 id="incomeValue" class="fw-semibold text-success">
                                    Rp {{ number_format(array_sum($chartKeuangan)) }}
                                </h5>
                                <p class="text-secondary" style="font-size: 12px;">Januari - Desember {{ date('Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 📝 Log Aktivitas Khusus Super Admin --}}
            @if(optional(auth()->user()->admin)->jabatan === 'super_admin')
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Log Aktivitas Sistem</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Waktu</th>
                                            <th>User</th>
                                            <th>Aktivitas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($adminLogs as $log)
                                        <tr>
                                            <td class="text-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                                            <td class="text-nowrap font-bold">{{ optional($log->user)->nama ?? 'Sistem' }}</td>
                                            <td>{{ $log->aktivitas }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Belum ada log aktivitas.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data dari Backend
        const chartKunjunganData = @json($chartKunjungan);
        const chartKeuanganData = @json($chartKeuangan);

        // Chart Kunjungan Pengguna
        const ctxVisit = document.getElementById('chart-profile-visit').getContext('2d');
        new Chart(ctxVisit, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Kunjungan',
                    data: chartKunjunganData,
                    borderColor: '#435ebe',
                    backgroundColor: 'rgba(67, 94, 190, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Chart Keuangan
        const ctxFinance = document.getElementById('financeChart').getContext('2d');
        const gradient = ctxFinance.createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, 'rgba(13, 167, 96, 0.35)');
        gradient.addColorStop(1, 'rgba(13, 167, 96, 0)');

        new Chart(ctxFinance, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pendapatan',
                    data: chartKeuanganData,
                    borderColor: '#0da760',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#0da760',
                    pointHoverRadius: 6,
                }]
            },
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
                            },
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(0) + 'jt';
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
            }
        });

        // Mini charts untuk regional (optional, bisa pakai ApexCharts)
    </script>
@endsection
