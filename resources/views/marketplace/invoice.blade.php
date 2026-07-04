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
            <strong>
                @if($pembayaran->snap_token)
                    Pembayaran Otomatis
                @else
                    {{ $pembayaran->metode_pembayaran }} {{ $pembayaran->va_bank ? '- ' . $pembayaran->va_bank : '' }}
                @endif
            </strong>

            @if(!$pembayaran->snap_token && $pembayaran->metode_pembayaran == 'VA BANK')
            <div class="alert alert-light mt-3 text-center">
                <small>Nomor Virtual Account</small>
                <h4 class="fw-bold mt-2">{{ $pembayaran->va_nomor }}</h4>
            </div>
            @endif

            <p class="mt-3">Total Pembayaran</p>
            <h4 class="fw-bold text-success">
                Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}
            </h4>

            @if ($pembayaran->status_validasi == 'pending')
                <small class="text-danger d-block mt-2">
                    Bayar sebelum:
                    {{ \Carbon\Carbon::parse($pembayaran->expired_at)->format('d M Y H:i') }}
                </small>

                @if($pembayaran->snap_token)
                    <button id="pay-button" class="btn btn-success w-100 mt-3 fw-bold shadow-sm" style="height: 50px; font-size: 1.1rem;">
                        <i class="bi bi-shield-lock me-2"></i> Bayar Sekarang
                    </button>
                @else
                    <a href="{{ route('pembayaran.proses', $pesanan->id_pesanan) }}" class="btn btn-success w-100 mt-3 fw-bold">
                        Simulasi Bayar Otomatis (Mock)
                    </a>
                @endif
            @elseif ($pembayaran->status_validasi == 'expired')
                <div class="alert alert-danger mt-3">
                    Pembayaran telah kedaluwarsa.
                </div>
            @endif

            <hr>
            
            <a href="{{ route('midtrans.cek_status', $pesanan->id_pesanan) }}" 
               class="btn btn-warning w-100 mb-2 fw-bold">
               <i class="bi bi-arrow-repeat me-1"></i> Sinkronisasi Status Midtrans (Lokal)
            </a>

            <a href="{{ route('marketplace.pesanan.saya') }}"
               class="btn btn-outline-primary w-100 mt-2">
                Kembali ke Pesanan Saya
            </a>

        </div>
    </div>

</div>

@if(isset($clientKey) && $pembayaran->snap_token)
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    <script>
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $pembayaran->snap_token }}', {
                onSuccess: function(result){
                    window.location.reload();
                },
                onPending: function(result){
                    window.location.reload();
                },
                onError: function(result){
                    alert('Pembayaran gagal!');
                },
                onClose: function(){
                    console.log('User closed the popup');
                }
            });
        };
    </script>
@endif
@endsection
