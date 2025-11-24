@extends('layouts.admin')

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
                <div class="col-md-8">{{ $pembayaran->id }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">ID Pesanan</div>
                <div class="col-md-8">{{ $pembayaran->order_id }}</div>
            </div>

            @if ($pembayaran->user)
            <div class="row mb-3">
                <div class="col-md-4 fw-bold">User</div>
                <div class="col-md-8">{{ $pembayaran->user->name }} ({{ $pembayaran->user->email }})</div>
            </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Metode Pembayaran</div>
                <div class="col-md-8">{{ ucfirst($pembayaran->metode) }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Total Bayar</div>
                <div class="col-md-8">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Status</div>
                <div class="col-md-8">
                    @if ($pembayaran->status === 'paid')
                        <span class="badge bg-success">Paid</span>
                    @elseif ($pembayaran->status === 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @else
                        <span class="badge bg-danger">Failed</span>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Tanggal Pembayaran</div>
                <div class="col-md-8">
                    {{ $pembayaran->tanggal_bayar ?? '-' }}
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-md-4 fw-bold">Dibuat</div>
                <div class="col-md-
