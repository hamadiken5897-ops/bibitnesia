@extends('layouts.admin.admin')

@section('title', 'Antrian Pengajuan Mitra')

@section('content')

    <div class="page-heading d-flex justify-content-between align-items-center">
        <h3>Antrian Pengajuan Mitra</h3>

        <div>
            <span class="badge bg-success fs-6 px-3 py-2">
                Total Antrian: {{ $totalAntrian }}
            </span>
        </div>
    </div>

    <div class="page-content">

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="pengajuanTabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#penjual">Penjual</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#kurir">Kurir</a>
            </li>
        </ul>

        <div class="tab-content">

            <!-- ================= PENJUAL ================= -->
            <div class="tab-pane fade show active" id="penjual">

                @if ($penjual->count() == 0)
                    <div class="alert alert-warning text-center">
                        Tidak ada antrian pengajuan penjual.
                    </div>
                @else
                    <div class="list-group">

                        @foreach ($penjual as $p)
                            <div class="list-group-item shadow-sm mb-3 p-3 rounded d-flex justify-content-between align-items-center"
                                style="border-left: 4px solid #41A67E;">

                                <div>
                                    <strong class="fs-5">{{ $p->user->nama }}</strong>
                                    <div class="text-muted small">ID Pengajuan: {{ $p->id_pengajuan }}</div>

                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 100%;"></div>
                                    </div>
                                </div>

                                <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#detailModal{{ $p->id_pengajuan }}">
                                    <i class="bi bi-eye"></i> Detail
                                </button>

                            </div>

                            @include('admin.services.pengajuan.modal-detail', [
                                'data' => $p,
                                'role' => 'penjual',
                            ])
                        @endforeach

                    </div>


                @endif

            </div>

            <!-- ================= KURIR ================= -->
            <div class="tab-pane fade" id="kurir">

                @if ($kurir->count() == 0)
                    <div class="alert alert-warning text-center">
                        Tidak ada antrian pengajuan kurir.
                    </div>
                @else
                    <div class="list-group">

                        @foreach ($kurir as $k)
                            <div class="list-group-item shadow-sm mb-3 p-3 rounded d-flex justify-content-between align-items-center"
                                style="border-left: 4px solid #41A67E;">
                                <div>
                                    <strong class="fs-5">{{ $k->user->nama }}</strong>
                                    <div class="text-muted small">ID Pengajuan: {{ $k->id_pengajuan }}</div>

                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: 100%;"></div>
                                    </div>
                                </div>

                                <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#detailModal{{ $k->id_pengajuan }}">
                                    <i class="bi bi-eye"></i> Detail
                                </button>

                            </div>

                            @include('admin.services.pengajuan.modal-detail', [
                                'data' => $k,
                                'role' => 'kurir',
                            ])
                        @endforeach

                    </div>

                @endif

            </div>

        </div>
    </div>
@endsection
