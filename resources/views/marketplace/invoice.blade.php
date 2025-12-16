@extends('layouts.marketplace.main')

@section('content')
<div class="container mt-5" style="max-width:600px">

    <div class="card shadow-sm">
        <div class="card-body">

            <h4 class="fw-bold mb-3">Selesaikan Pembayaran</h4>

            <p class="mb-1">Invoice</p>
            <h6 class="fw-bold">{{ $pesanan->kode_invoice }}</h6>

            <hr>

            <p class="mb-1">Metode Pembayaran</p>
            <strong>Virtual Account {{ $pesanan->pembayaran->va_bank }}</strong>

            <div class="alert alert-light mt-3">
                <small>Nomor Virtual Account</small>
                <h5 class="fw-bold">{{ $pesanan->pembayaran->va_nomor }}</h5>
            </div>

            <p>Total Pembayaran</p>
            <h4 class="fw-bold text-success">
                Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}
            </h4>

            <small class="text-muted">
                Silakan lakukan pembayaran sebelum batas waktu.
            </small>

            <hr>

            <a href="{{ route('marketplace.pesanan.saya') }}"
               class="btn btn-outline-primary w-100 mt-2">
                Kembali ke Pesanan Saya
            </a>

        </div>
    </div>

</div>
@endsection
