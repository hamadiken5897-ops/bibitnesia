@extends('layouts.admin.admin')

@section('title', 'Detail Pengiriman')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Detail Pengiriman #{{ $pengiriman->id_pengiriman }}</h3>
        <a href="{{ route('admin.pengiriman.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="page-content">
    <div class="row">
        <!-- Informasi Status & Resi -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Informasi Pengiriman</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <span class="text-muted d-block">Status Saat Ini</span>
                            @if($pengiriman->status_pengiriman == 'diproses')
                                <span class="badge bg-warning fs-6 mt-1">Diproses</span>
                            @elseif($pengiriman->status_pengiriman == 'dikirim')
                                <span class="badge bg-info fs-6 mt-1">Dikirim</span>
                            @elseif($pengiriman->status_pengiriman == 'selesai')
                                <span class="badge bg-success fs-6 mt-1">Selesai</span>
                            @else
                                <span class="badge bg-secondary fs-6 mt-1">{{ ucfirst($pengiriman->status_pengiriman) }}</span>
                            @endif
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="text-muted d-block">No. Resi</span>
                            <span class="fw-bold fs-5">{{ $pengiriman->no_resi ?? 'Belum ada resi' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="text-muted d-block">Ekspedisi / Kurir</span>
                            <span class="fw-bold fs-5">{{ $pengiriman->kurir->user->nama ?? 'Belum ditugaskan' }}</span>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="text-muted d-block">Tanggal Pengiriman</span>
                            <span class="fw-bold">{{ $pengiriman->tanggal_pengiriman ? \Carbon\Carbon::parse($pengiriman->tanggal_pengiriman)->format('d M Y, H:i') : 'Menunggu penjemputan' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($pengiriman->pesanan && $pengiriman->pesanan->riwayat->count() > 0)
        <!-- Riwayat Status Pesanan (Timeline Vertikal) -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Status Pengiriman</h5>
                    <div class="timeline-container mt-3">
                        @foreach($pengiriman->pesanan->riwayat as $riwayat)
                            <div class="timeline-item {{ $loop->first ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="timeline-date text-muted small">
                                        {{ \Carbon\Carbon::parse($riwayat->created_at)->translatedFormat('d M Y, H:i') }}
                                    </div>
                                    <div class="timeline-title fw-bold {{ $loop->first ? 'text-success' : '' }}">
                                        {{ $riwayat->status }}
                                    </div>
                                    <div class="timeline-desc text-muted small mt-1">
                                        {{ $riwayat->deskripsi }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Detail Entitas (Pembeli, Penjual, Alamat) -->
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">Detail Pihak Terkait</h5>
                    
                    <div class="mb-4">
                        <h6 class="text-primary"><i class="bi bi-shop"></i> Informasi Penjual (Toko)</h6>
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <td width="150" class="text-muted">Nama Toko</td>
                                <td>: <strong>{{ $pengiriman->pesanan->detailPesanan->first()?->produk->penjual->nama_toko ?? 'N/A' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nama Penjual</td>
                                <td>: {{ $pengiriman->pesanan->detailPesanan->first()?->produk->penjual->user->nama ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-success"><i class="bi bi-person"></i> Informasi Pembeli</h6>
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <td width="150" class="text-muted">Nama</td>
                                <td>: <strong>{{ $pengiriman->pesanan->user->nama ?? 'N/A' }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">No. HP</td>
                                <td>: {{ $pengiriman->pesanan->user->no_telepon ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>

                    <div>
                        <h6 class="text-info"><i class="bi bi-geo-alt-fill"></i> Alamat Tujuan</h6>
                        <div class="p-3 bg-light rounded">
                            {{ $pengiriman->alamat_tujuan ?? $pengiriman->pesanan->alamat ?? 'Alamat tidak tersedia' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Pesanan -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">Informasi Pesanan</h5>
                    
                    <div class="mb-3">
                        <span class="text-muted d-block">ID Pesanan</span>
                        <strong>{{ $pengiriman->id_pesanan }}</strong>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted d-block">Status Pesanan</span>
                        <span class="badge bg-primary">{{ $pengiriman->pesanan->status_pesanan ?? 'N/A' }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted d-block">Total Harga (termasuk ongkir)</span>
                        <strong>Rp {{ number_format($pengiriman->pesanan->total_harga ?? 0, 0, ',', '.') }}</strong>
                    </div>
                    
                    <hr>
                    <span class="text-muted d-block mb-2">Item Produk:</span>
                    <ul class="list-unstyled">
                        @foreach($pengiriman->pesanan->detailPesanan as $detail)
                            <li class="mb-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="d-block fw-bold">{{ $detail->produk->nama_produk ?? 'Produk tidak ditemukan' }}</small>
                                    <small class="text-muted">{{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</small>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<style>
    .timeline-container {
        position: relative;
        padding-left: 30px;
        margin-bottom: 20px;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-marker {
        position: absolute;
        left: -22px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #d1d5db;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #d1d5db;
        z-index: 2;
    }

    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: -17px;
        top: 15px;
        bottom: -5px;
        width: 2px;
        background-color: #e5e7eb;
        z-index: 1;
    }

    .timeline-item.active .timeline-marker {
        background-color: #198754; /* success color */
        box-shadow: 0 0 0 2px #198754;
    }
</style>
@endsection
