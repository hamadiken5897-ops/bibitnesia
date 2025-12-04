@extends('layouts.kurir.kurir')

@section('title', 'Pembayaran - BibitNesia Kurir')
@section('page-title', 'Manajemen Pembayaran')

@section('content')

<style>
    .card {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-radius: 12px;
        transition: .3s ease;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .stats-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    .green { background: #0da760; }
    .yellow { background: #f1c40f; }
    .red { background: #e74c3c; }
    .badge-lunas {
        padding: 5px 10px;
        border-radius: 8px;
        background: #0da76020;
        color: #0da760;
        font-weight: 600;
    }
    .badge-pending {
        padding: 5px 10px;
        border-radius: 8px;
        background: #f1c40f20;
        color: #c49b08;
        font-weight: 600;
    }
</style>

<section class="row">

    {{-- STATISTIK PEMBAYARAN --}}
    <div class="col-12 mb-3">
        <div class="row">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon green me-3">
                            <i class="iconly-boldWallet"></i>
                        </div>
                        <div>
                            <h6 class="text-muted">Total Lunas</h6>
                            <h5 class="fw-bold">152</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3 col-md-6">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon yellow me-3">
                            <i class="iconly-boldTimeCircle"></i>
                        </div>
                        <div>
                            <h6 class="text-muted">Pending</h6>
                            <h5 class="fw-bold">17</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3 col-md-6">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon red me-3">
                            <i class="iconly-boldCloseSquare"></i>
                        </div>
                        <div>
                            <h6 class="text-muted">Gagal</h6>
                            <h5 class="fw-bold">4</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3 col-md-6">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon green me-3">
                            <i class="iconly-boldWallet"></i>
                        </div>
                        <div>
                            <h6 class="text-muted">Total Pendapatan</h6>
                            <h5 class="fw-bold">Rp 27.450.000</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL PEMBAYARAN --}}
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header">
                <h5 class="fw-semibold">Daftar Pembayaran</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Order</th>
                            <th>Nama Pelanggan</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>#ORD-2025-112</td>
                            <td>Rina Ayu</td>
                            <td>Rp 95.000</td>
                            <td><span class="badge-lunas">Lunas</span></td>
                            <td>03 Jan 2025</td>
                            <td>
                                <button class="btn btn-success btn-sm">Detail</button>
                            </td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td>#ORD-2025-103</td>
                            <td>Ardi Wijaya</td>
                            <td>Rp 65.000</td>
                            <td><span class="badge-pending">Pending</span></td>
                            <td>02 Jan 2025</td>
                            <td>
                                <button class="btn btn-secondary btn-sm">Follow Up</button>
                            </td>
                        </tr>

                        <tr>
                            <td>3</td>
                            <td>#ORD-2025-099</td>
                            <td>Nia Putri</td>
                            <td>Rp 125.000</td>
                            <td><span class="badge-lunas">Lunas</span></td>
                            <td>30 Des 2024</td>
                            <td>
                                <button class="btn btn-success btn-sm">Detail</button>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</section>
@endsection
