@extends('layouts.marketplace.main')

@section('content')
    <div class="container mt-4">

        <h3 class="fw-bold mb-4">Pesanan Saya</h3>

        @if ($pesanan->isEmpty())
            <div class="alert alert-info">
                Anda belum memiliki pesanan.
            </div>
        @else
            @foreach ($pesanan as $p)
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <div>
                            <span class="badge bg-warning">{{ $p->status_pesanan }}</span>
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
                        
                        <div class="text-end mt-3">
                            @if ($p->status_pesanan == 'Menunggu Pembayaran')
                                <a href="{{ route('marketplace.invoice', $p->id_pesanan) }}"
                                    class="btn btn-primary">
                                    Lihat Invoice & Bayar
                                </a>
                            @elseif ($p->status_pesanan == 'Pesanan dalam pengiriman' || $p->status_pesanan == 'Pesanan Selesai')
                                @if($p->status_pesanan == 'Pesanan dalam pengiriman')
                                <form action="{{ route('marketplace.pesanan.selesai', $p->id_pesanan) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Konfirmasi Barang Diterima</button>
                                </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

    </div>
@endsection
