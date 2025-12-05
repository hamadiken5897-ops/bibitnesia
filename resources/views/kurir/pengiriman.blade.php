@extends('layouts.kurir.kurir')

@section('title', 'Pengiriman - BibitNesia Kurir')
@section('page-title', 'Daftar Pengiriman')

@section('content')

<style>
    .card {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-radius: 12px;
        transition: 0.3s ease;
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

    .blue { background: #3498db; }
    .green { background: #0da760; }
    .yellow { background: #f1c40f; }
    .red { background: #e74c3c; }

    .badge-proses {
        padding: 6px 10px;
        border-radius: 8px;
        background: #3498db20;
        color: #2980b9;
        font-weight: 600;
    }
    .badge-antar {
        padding: 6px 10px;
        border-radius: 8px;
        background: #f1c40f20;
        color: #c49b08;
        font-weight: 600;
    }
    .badge-selesai {
        padding: 6px 10px;
        border-radius: 8px;
        background: #0da76020;
        color: #0da760;
        font-weight: 600;
    }
</style>

<section class="row">

    {{-- STATISTIK RINGKAS --}}
    <div class="col-12 mb-3">
        <div class="row">

            <div class="col-6 col-lg-3 col-md-6">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon blue me-3">
                            <i class="iconly-boldLocation"></i>
                        </div>
                        <div>
                            <h6 class="text-muted">Total Pengiriman</h6>
                            <h5 class="fw-bold">185</h5>
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
                            <h6 class="text-muted">Sedang Diantar</h6>
                            <h5 class="fw-bold">14</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3 col-md-6">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon green me-3">
                            <i class="iconly-boldShieldDone"></i>
                        </div>
                        <div>
                            <h6 class="text-muted">Terkirim</h6>
                            <h5 class="fw-bold">146</h5>
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
                            <h6 class="text-muted">Pengiriman Gagal</h6>
                            <h5 class="fw-bold">5</h5>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- TABEL PENGIRIMAN --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="fw-semibold">Daftar Pengiriman</h5>
            </div>

            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Order</th>
                            <th>Penerima</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- PROSES --}}
                        <tr>
                            <td>1</td>
                            <td>#SEND-12234</td>
                            <td>Budi Santoso</td>
                            <td>Jl. Kenanga No. 12, Bandung</td>
                            <td><span class="badge-proses">Proses</span></td>
                            <td>03 Jan 2025</td>
                            <td>
                                <button class="btn btn-primary btn-sm">Mulai Antar</button>
                            </td>
                        </tr>

                        {{-- SEDANG DIANTAR --}}
                        <tr>
                            <td>2</td>
                            <td>#SEND-12198</td>
                            <td>Rina Putri</td>
                            <td>Jl. Melati No. 52, Cimahi</td>
                            <td><span class="badge-antar">Diantar</span></td>
                            <td>03 Jan 2025</td>
                            <td>
                                <button class="btn btn-success btn-sm">Selesaikan</button>
                            </td>
                        </tr>

                        {{-- SELESAI --}}
                        <tr>
                            <td>3</td>
                            <td>#SEND-12100</td>
                            <td>Agus Firmansyah</td>
                            <td>Jl. Pahlawan 21, Bandung</td>
                            <td><span class="badge-selesai">Selesai</span></td>
                            <td>02 Jan 2025</td>
                            <td>
                                <button class="btn btn-outline-success btn-sm">Detail</button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>

@endsection
