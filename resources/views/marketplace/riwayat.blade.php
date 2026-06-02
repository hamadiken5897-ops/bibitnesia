@extends('layouts.marketplace.main')

@section('content')
<div class="container mt-4">

    <h3 class="fw-bold mb-4">Riwayat Pesanan</h3>

    @if ($riwayat->count() == 0)
        <div class="alert alert-info">
            Belum ada riwayat pesanan.
        </div>
    @else
        @foreach ($riwayat as $item)
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">Invoice: {{ $item->kode_invoice ?? $item->id_pesanan }}</h6>
                    <small class="text-muted">{{ $item->created_at->format('d M Y') }}</small>
                </div>
                <div class="card-body">
                    @foreach ($item->detailPesanan as $detail)
                        <div class="d-flex {{ !$loop->last ? 'mb-3 border-bottom pb-3' : '' }}">
                            <img src="{{ asset('storage/' . ($detail->produk->foto_produk1 ?? '')) }}"
                                class="me-3"
                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">

                            <div class="flex-grow-1">
                                <h6 class="fw-bold">{{ $detail->produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</h6>
                                <p class="m-0 text-muted small">
                                    {{ $detail->jumlah }} item x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                        <div>
                            <span class="text-muted d-block small">Total Belanja</span>
                            <h6 class="fw-bold mb-0">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</h6>
                        </div>
                        <span class="badge bg-secondary px-3 py-2">
                            {{ ucfirst($item->status_pesanan) }}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

</div>
@endsection
