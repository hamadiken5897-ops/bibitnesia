@extends('layouts.marketplace.main')

@section('content')
<style>
    .nav-tabs-custom {
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        background: #fff;
        padding: 0;
        margin-bottom: 20px;
    }
    .nav-tabs-custom .nav-item {
        flex-grow: 1;
        text-align: center;
    }
    .nav-tabs-custom .nav-link {
        color: #555;
        border: none;
        padding: 15px 10px;
        font-weight: 500;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
    }
    .nav-tabs-custom .nav-link:hover {
        color: #27ae60;
    }
    .nav-tabs-custom .nav-link.active {
        color: #27ae60;
        border-bottom: 3px solid #27ae60;
        background: transparent;
    }
    .order-card {
        border-radius: 8px;
        border: 1px solid #eaeaea;
        margin-bottom: 15px;
        background: #fff;
    }
    .order-header {
        padding: 12px 20px;
        border-bottom: 1px solid #f1f1f1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .order-shop-name {
        font-weight: 600;
        color: #333;
    }
    .order-status-text {
        color: #e67e22;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
    }
    .order-body {
        padding: 15px 20px;
    }
    .order-footer {
        padding: 15px 20px;
        border-top: 1px solid #f1f1f1;
        background: #fafbfc;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
    }
    .total-label {
        font-size: 0.9rem;
        color: #777;
        margin-right: 10px;
    }
    .total-amount {
        font-size: 1.2rem;
        font-weight: 700;
        color: #e74c3c;
    }
    .btn-action {
        min-width: 130px;
        margin-left: 15px;
    }
</style>

<div class="container mt-4 mb-5">
    <h3 class="fw-bold mb-4">Pesanan Saya</h3>

    <!-- Tabs -->
    <ul class="nav nav-tabs-custom">
        <li class="nav-item">
            <a class="nav-link {{ $status == 'semua' ? 'active' : '' }}" href="{{ route('marketplace.pesanan.saya', ['status' => 'semua']) }}">Semua</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'belum-bayar' ? 'active' : '' }}" href="{{ route('marketplace.pesanan.saya', ['status' => 'belum-bayar']) }}">Belum Bayar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'menunggu-konfirmasi' ? 'active' : '' }}" href="{{ route('marketplace.pesanan.saya', ['status' => 'menunggu-konfirmasi']) }}">Menunggu Konfirmasi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'dikemas' ? 'active' : '' }}" href="{{ route('marketplace.pesanan.saya', ['status' => 'dikemas']) }}">Sedang Dikemas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'dikirim' ? 'active' : '' }}" href="{{ route('marketplace.pesanan.saya', ['status' => 'dikirim']) }}">Dikirim</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'selesai' ? 'active' : '' }}" href="{{ route('marketplace.pesanan.saya', ['status' => 'selesai']) }}">Selesai</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'dibatalkan' ? 'active' : '' }}" href="{{ route('marketplace.pesanan.saya', ['status' => 'dibatalkan']) }}">Dibatalkan</a>
        </li>
    </ul>

    <!-- Daftar Pesanan -->
    @if ($pesanan->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-box-seam text-muted" style="font-size: 4rem;"></i>
            <h5 class="mt-3 text-muted">Belum ada pesanan</h5>
        </div>
    @else
        @foreach ($pesanan as $p)
            <div class="order-card shadow-sm">
                <div class="order-header">
                    <div class="order-shop-name">
                        <i class="bi bi-shop me-2 text-success"></i> 
                        {{ $p->detailPesanan->first()->produk->penjual->nama_toko ?? 'Bibitnesia' }}
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="order-status-text">
                            @if($p->status_pesanan == 'Menunggu Pembayaran')
                                Belum Bayar
                            @elseif($p->status_pesanan == 'Menunggu konfirmasi penjual')
                                Menunggu Konfirmasi
                            @elseif($p->status_pesanan == 'Pesanan sedang diproses')
                                Sedang Dikemas
                            @elseif(in_array($p->status_pesanan, ['Pesanan dalam pengiriman', 'Sampai Tujuan']))
                                Dikirim
                            @elseif(in_array($p->status_pesanan, ['Pesanan selesai', 'Pesanan Selesai']))
                                Selesai
                            @else
                                {{ $p->status_pesanan }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="order-body" style="cursor: pointer;" onclick="window.location='{{ route('marketplace.pesanan.saya.show', $p->id_pesanan) }}'">
                    @foreach ($p->detailPesanan as $detail)
                        @if($detail->produk)
                        <div class="d-flex mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <img src="{{ asset('storage/' . $detail->produk->foto_produk1) }}" class="rounded border" style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="ms-3 flex-grow-1">
                                <h6 class="fw-bold mb-1">{{ $detail->produk->nama_produk }}</h6>
                                <div class="text-muted small mb-2">x{{ $detail->jumlah }}</div>
                                <div class="fw-bold text-dark">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                <div class="order-footer">
                    <div class="d-flex flex-column text-end align-items-end">
                        <div class="d-flex align-items-center mb-3">
                            <span class="total-label">Total Pesanan:</span>
                            <span class="total-amount">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex">
                            <a href="{{ route('marketplace.pesanan.saya.show', $p->id_pesanan) }}" class="btn btn-outline-success btn-action">Lihat Detail</a>
                            
                            @if ($p->status_pesanan == 'Menunggu Pembayaran')
                                <a href="{{ route('marketplace.invoice', $p->id_pesanan) }}" class="btn btn-primary btn-action">Bayar Sekarang</a>
                            @elseif ($p->status_pesanan == 'Sampai Tujuan')
                                <form action="{{ route('marketplace.pesanan.selesai', $p->id_pesanan) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-action">Pesanan Diterima</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
