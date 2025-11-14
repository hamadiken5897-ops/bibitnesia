@extends('layouts.admin')

@section('title', 'Dashboard - Mazer Admin')
@section('page-title', 'Profile Statistics')

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
                                    <h6 class="text-muted font-semibold">Profile Views</h6>
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
                                    <h6 class="text-muted font-semibold">Followers</h6>
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
                                        <i class="iconly-boldAdd-User"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Following</h6>
                                    <h6 class="font-extrabold mb-0">80.000</h6>
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
                                    <h6 class="text-muted font-semibold">Saved Post</h6>
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
                            <h4>Profile Visit</h4>
                        </div>
                        <div class="card-body">
                            <div id="chart-profile-visit"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Regional Chart Section --}}
            <div class="row">
                <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Profile Visit</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-primary" width="32" height="32" fill="blue" style="width:10px">
                                            <use
                                                xlink:href="{{ asset('dist/assets/vendors/bootstrap-icons/bootstrap-icons.svg#circle-fill') }}" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">Europe</h5>
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
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-success" width="32" height="32" fill="blue" style="width:10px">
                                            <use
                                                xlink:href="{{ asset('dist/assets/vendors/bootstrap-icons/bootstrap-icons.svg#circle-fill') }}" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">America</h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h5 class="mb-0">375</h5>
                                </div>
                                <div class="col-12">
                                    <div id="chart-america"></div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <svg class="bi text-danger" width="32" height="32" fill="blue" style="width:10px">
                                            <use
                                                xlink:href="{{ asset('dist/assets/vendors/bootstrap-icons/bootstrap-icons.svg#circle-fill') }}" />
                                        </svg>
                                        <h5 class="mb-0 ms-3">Indonesia</h5>
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

                {{-- Latest Comments --}}
                {{-- Ganti bagian Latest Comments dengan ini --}}
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
                            plugins: { legend: { display: false } },
                            scales: {
                                x: {
                                    ticks: { color: '#666', font: { size: 11 } },
                                    grid: { color: 'rgba(0,0,0,0.05)' },
                                },
                                y: {
                                    ticks: { color: '#999', font: { size: 11 } },
                                    grid: { color: 'rgba(0,0,0,0.04)' },
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

        {{-- Sidebar Section --}}
        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-body py-4 px-5">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl">
                            <img src="{{ asset('dist/assets/images/faces/1.jpg') }}" alt="Face 1">
                        </div>
                        <div class="ms-3 name">
                            <h5 class="font-bold">John Duck</h5>
                            <h6 class="text-muted mb-0">@johnducky</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>Recent Messages</h4>
                </div>
                <div class="card-content pb-4">
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="{{ asset('dist/assets/images/faces/4.jpg') }}">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">Hank Schrader</h5>
                            <h6 class="text-muted mb-0">@johnducky</h6>
                        </div>
                    </div>
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="{{ asset('dist/assets/images/faces/5.jpg') }}">
                        </div>
                        <div class="name ms-4">
                          <h5 class="mb-1">Dean Winchester</h5>
                            <h6 class="text-muted mb-0">@imdean</h6>
                        </div>
                    </div>
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="{{ asset('dist/assets/images/faces/1.jpg') }}">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">John Dodol</h5>
                            <h6 class="text-muted mb-0">@dodoljohn</h6>
                        </div>
                    </div>
                    <div class="px-4">
                        <button class='btn btn-block btn-xl btn-light-primary font-bold mt-3'>Start Conversation</button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>Visitors Profile</h4>
                </div>
                <div class="card-body">
                    <div id="chart-visitors-profile"></div>
                </div>
            </div>
        </div>
    </section>
@endsection