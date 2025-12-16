@extends('layouts.marketplace.main')

@section('content')
    <div class="container mt-4">

        <h3 class="fw-bold mb-4">Pesanan Saya</h3>

        @if ($pesanan->isEmpty())
            <div class="alert alert-info">
                Anda belum memiliki pesanan.
            </div>
        @else
            @foreach ($pesanan as $item)
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center justify-content-between">

                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/' . $item->foto_produk1) }}" class="rounded me-3"
                                style="width:90px;height:90px;object-fit:cover">

                            <div>
                                <h6 class="fw-bold mb-1">{{ $item->nama_produk }}</h6>

                                <small class="text-muted d-block">
                                    Jumlah: {{ $item->jumlah }}
                                </small>

                                <small class="text-muted d-block">
                                    Total:
                                    <strong class="text-success">
                                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                    </strong>
                                </small>

                                <span class="badge bg-warning mt-2">
                                    {{ $item->status_pesanan }}
                                </span>
                            </div>
                        </div>

                        <div class="text-end">
                            <small class="text-muted d-block mb-2">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                            </small>

                            @if ($item->status_pesanan == 'Menunggu Pembayaran')
                                <a href="{{ route('pembayaran.proses', $item->id_pesanan) }}"
                                    class="btn btn-primary btn-sm">
                                    Bayar Sekarang
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach
        @endif

    </div>
@endsection
