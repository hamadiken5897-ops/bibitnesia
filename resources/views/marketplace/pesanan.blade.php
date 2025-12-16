@extends('layouts.marketplace.main')

@section('content')
    <div class="container mt-4">

        <h3 class="fw-bold mb-4">Pesanan Saya</h3>

        @if ($pesanan->count() == 0)
            <div class="alert alert-info">
                Anda belum memiliki pesanan.
            </div>
        @else
            @foreach ($pesanan as $item)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body d-flex">

                        {{-- Gambar Produk --}}
                        <img src="{{ asset('storage/' . $item->produk->gambar) }}"
                            style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;" class="me-3">

                        {{-- Detail Produk --}}
                        <div class="flex-grow-1">
                            <h5 class="fw-bold">{{ $item->produk->nama }}</h5>
                            <p class="m-0">Jumlah: {{ $item->jumlah }}</p>
                            <p class="m-0">Total: Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                            <span class="badge bg-success mt-2">
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                        <span
                            class="badge 
                    {{ $item->status == 'menunggu_pembayaran' ? 'bg-warning' : 'bg-success' }}">
                            {{ str_replace('_', ' ', ucfirst($item->status)) }}
                        </span>

                        {{-- Detail Pesanan --}}
                        <div class="text-end">
                            <small class="text-muted">
                                {{ $item->created_at->format('d M Y') }}
                            </small>
                        </div>

                        @if ($item->status == 'menunggu_pembayaran')
                            <a href="{{ route('marketplace.invoice', $item->id_pesanan) }}"
                                class="btn btn-sm btn-outline-primary mt-2">
                                Bayar Sekarang
                            </a>
                        @endif


                    </div>
                </div>
            @endforeach
        @endif

    </div>
@endsection
