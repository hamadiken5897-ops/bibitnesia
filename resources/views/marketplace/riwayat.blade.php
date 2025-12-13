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
                <div class="card-body d-flex">

                    <img src="{{ asset('storage/' . $item->produk->gambar) }}"
                        class="me-3"
                        style="width: 90px; height: 90px; object-fit: cover; border-radius: 10px;">

                    <div class="flex-grow-1">
                        <h5 class="fw-bold">{{ $item->produk->nama }}</h5>
                        <p class="m-0">Jumlah: {{ $item->jumlah }}</p>
                        <p class="m-0">Total: Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>

                        <span class="badge bg-secondary mt-2">
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>

                    <div class="text-end">
                        <small class="text-muted">
                            {{ $item->created_at->format('d M Y') }}
                        </small>
                    </div>

                </div>
            </div>
        @endforeach
    @endif

</div>
@endsection
