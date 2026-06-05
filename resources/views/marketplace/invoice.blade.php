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
            <strong>Virtual Account {{ $pembayaran->va_bank }}</strong>

            <div class="alert alert-light mt-3 text-center">
                <small>Nomor Virtual Account</small>
                <h4 class="fw-bold mt-2">{{ $pembayaran->va_nomor }}</h4>
            </div>

            <p>Total Pembayaran</p>
            <h4 class="fw-bold text-success">
                Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}
            </h4>

            @if ($pembayaran->status_validasi == 'pending')
                <small class="text-danger d-block mt-2">
                    Bayar sebelum:
                    {{ \Carbon\Carbon::parse($pembayaran->expired_at)->format('d M Y H:i') }}
                </small>

                <a href="{{ route('pembayaran.proses', $pesanan->id_pesanan) }}" class="btn btn-success w-100 mt-3 fw-bold">
                    Simulasi Bayar Otomatis (Midtrans Mock)
                </a>
            @elseif ($pembayaran->status_validasi == 'expired')
                <div class="alert alert-danger mt-3">
                    Pembayaran telah kedaluwarsa.
                </div>
            @endif

            <hr>

            <a href="{{ route('marketplace.pesanan.saya') }}"
               class="btn btn-outline-primary w-100 mt-2">
                Kembali ke Pesanan Saya
            </a>

        </div>
    </div>

</div>
@endsection
