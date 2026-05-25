@extends('layouts.admin.admin')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Detail Pembayaran</h3>

        <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="page-content">

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Informasi Pembayaran</h4>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">ID Pembayaran</div>
                <div class="col-md-8">{{ $pembayaran->id_pembayaran }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">ID Pesanan</div>
                <div class="col-md-8">{{ $pembayaran->id_pesanan }}</div>
            </div>

            @if ($pembayaran->user)
            <div class="row mb-3">
                <div class="col-md-4 fw-bold">User</div>
                <div class="col-md-8">{{ $pembayaran->user->name }} ({{ $pembayaran->user->email }})</div>
            </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Metode Pembayaran</div>
                <div class="col-md-8">{{ ucfirst($pembayaran->metode_pembayaran) }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Total Bayar</div>
                <div class="col-md-8">Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Status</div>
                <div class="col-md-8">
                    @if ($pembayaran->status_validasi === 'paid' || strtolower($pembayaran->status_validasi ?? '') === 'valid' || $pembayaran->status_validasi === 'sudah_bayar')
                        <span class="badge bg-success">Paid</span>
                    @elseif ($pembayaran->status_validasi === 'pending' || $pembayaran->status_validasi === 'belum_bayar')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @else
                        <span class="badge bg-danger">Failed</span>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Tanggal Pembayaran</div>
                <div class="col-md-8">
                    {{ $pembayaran->tanggal_pembayaran ?? '-' }}
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Dibuat</div>
                <div class="col-md-8">{{ $pembayaran->created_at ? $pembayaran->created_at->format('d-m-Y H:i') : '-' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Diupdate</div>
                <div class="col-md-8">{{ $pembayaran->updated_at ? $pembayaran->updated_at->format('d-m-Y H:i') : '-' }}</div>
            </div>

        </div>
    </div>

</div>
@endsection
