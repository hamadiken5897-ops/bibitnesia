@extends('layouts.marketplace.main')

@section('content')
    <div class="container mt-4">

        <h3 class="fw-bold mb-4">Riwayat Pesanan</h3>

        @if ($riwayat->isEmpty())
            <div class="alert alert-info">
                Anda belum memiliki riwayat pesanan yang selesai.
            </div>
        @else
            @foreach ($riwayat as $p)
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <div>
                            <span class="badge bg-success">{{ $p->status_pesanan }}</span>
                            <small class="text-muted ms-2">{{ \Carbon\Carbon::parse($p->created_at)->format('d M Y H:i') }}</small>
                        </div>
                        <div>
                            <span class="fw-bold">Total Invoice: <span class="text-success">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</span></span>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach ($p->detailPesanan as $detail)
                            @if($detail->produk)
                            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                <img src="{{ asset('storage/' . $detail->produk->foto_produk1) }}" class="rounded me-3"
                                    style="width:70px;height:70px;object-fit:cover">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $detail->produk->nama_produk }}</h6>
                                    <small class="text-muted d-block">
                                        {{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                    </small>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

    </div>
@endsection
