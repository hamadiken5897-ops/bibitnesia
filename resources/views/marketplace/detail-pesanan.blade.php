@extends('layouts.marketplace.main')

@section('content')
<style>
    .tracking-timeline {
        position: relative;
        padding-top: 30px;
        padding-bottom: 30px;
        margin-bottom: 30px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-left: 10%;
        padding-right: 10%;
    }
    .tracking-step {
        text-align: center;
        position: relative;
        flex: 1;
        z-index: 2;
    }
    .tracking-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #e0e0e0;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 1.5rem;
        z-index: 2;
        position: relative;
    }
    .tracking-icon.active {
        background: #27ae60;
        box-shadow: 0 0 0 5px rgba(39, 174, 96, 0.2);
    }
    .tracking-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #777;
    }
    .tracking-label.active {
        color: #27ae60;
    }
    .tracking-line {
        position: absolute;
        top: 55px; /* Adjust based on padding/icon size */
        left: 15%;
        right: 15%;
        height: 4px;
        background: #e0e0e0;
        z-index: 1;
    }
    .tracking-line-progress {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        background: #27ae60;
        transition: width 0.3s ease;
    }
    
    .detail-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        padding: 20px;
        margin-bottom: 20px;
    }
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 15px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
</style>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0"><a href="{{ route('marketplace.pesanan.saya') }}" class="text-dark"><i class="bi bi-arrow-left me-2"></i></a> Detail Pesanan</h4>
        <span class="text-muted">ID Pesanan: {{ $pesanan->id_pesanan }}</span>
    </div>

    @php
        $statusIndex = 0;
        if($pesanan->status_pesanan == 'Menunggu Pembayaran') $statusIndex = 0;
        elseif($pesanan->status_pesanan == 'Menunggu konfirmasi penjual') $statusIndex = 1;
        elseif($pesanan->status_pesanan == 'Pesanan sedang diproses') $statusIndex = 2;
        elseif(in_array($pesanan->status_pesanan, ['Pesanan dalam pengiriman', 'Sampai Tujuan'])) $statusIndex = 3;
        elseif(in_array($pesanan->status_pesanan, ['Pesanan selesai', 'Pesanan Selesai'])) $statusIndex = 4;
        
        $progressWidth = ($statusIndex / 4) * 100;
    @endphp

    <!-- Progress Bar -->
    <div class="tracking-timeline position-relative">
        <div class="tracking-line">
            <div class="tracking-line-progress" style="width: {{ $progressWidth }}%;"></div>
        </div>

        <div class="tracking-step">
            <div class="tracking-icon {{ $statusIndex >= 0 ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="tracking-label {{ $statusIndex >= 0 ? 'active' : '' }}">Pesanan Dibuat</div>
        </div>
        <div class="tracking-step">
            <div class="tracking-icon {{ $statusIndex >= 1 ? 'active' : '' }}">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="tracking-label {{ $statusIndex >= 1 ? 'active' : '' }}">Menunggu Konfirmasi</div>
        </div>
        <div class="tracking-step">
            <div class="tracking-icon {{ $statusIndex >= 2 ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="tracking-label {{ $statusIndex >= 2 ? 'active' : '' }}">Sedang Dikemas</div>
        </div>
        <div class="tracking-step">
            <div class="tracking-icon {{ $statusIndex >= 3 ? 'active' : '' }}">
                <i class="bi bi-truck"></i>
            </div>
            <div class="tracking-label {{ $statusIndex >= 3 ? 'active' : '' }}">Dikirim</div>
        </div>
        <div class="tracking-step">
            <div class="tracking-icon {{ $statusIndex >= 4 ? 'active' : '' }}">
                <i class="bi bi-star"></i>
            </div>
            <div class="tracking-label {{ $statusIndex >= 4 ? 'active' : '' }}">Selesai</div>
        </div>
    </div>

    @if($pesanan->status_pesanan == 'Pesanan ditolak')
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Pesanan ini telah ditolak atau dibatalkan.
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Riwayat Status Pesanan (Timeline Vertikal) -->
            @if($pesanan->riwayat->count() > 0)
            <div class="detail-card">
                <div class="section-title">Status Pengiriman</div>
                <div class="timeline-container mt-3">
                    @foreach($pesanan->riwayat as $riwayat)
                        <div class="timeline-item {{ $loop->first ? 'active' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <div class="timeline-date text-muted small">
                                    {{ \Carbon\Carbon::parse($riwayat->created_at)->translatedFormat('d M Y, H:i') }}
                                </div>
                                <div class="timeline-title fw-bold {{ $loop->first ? 'text-success' : '' }}">
                                    {{ $riwayat->status }}
                                </div>
                                <div class="timeline-desc text-muted small mt-1">
                                    {{ $riwayat->deskripsi }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="detail-card">
                <div class="section-title">Status Pengiriman</div>
                <div class="alert alert-info mt-3 border-0">
                    <i class="bi bi-info-circle me-2"></i> Detail riwayat pengiriman secara vertikal belum tersedia untuk pesanan lama ini. 
                </div>
            </div>
            @endif

            <!-- Informasi Pengiriman -->
            <div class="detail-card">
                <div class="section-title">Alamat Pengiriman</div>
                <div class="mb-3">
                    <div class="fw-bold">{{ auth()->user()->nama }}</div>
                    <div class="text-muted">{{ auth()->user()->no_telepon ?? '-' }}</div>
                    <div class="mt-2">{{ $pesanan->alamat ?? 'Alamat tidak tersedia' }}</div>
                </div>

                @if($pesanan->pengiriman)
                <div class="section-title mt-4">Informasi Kurir</div>
                <div class="row">
                    <div class="col-sm-4 text-muted mt-2">Kurir</div>
                    <div class="col-sm-8 fw-bold text-uppercase mt-2">
                        {{ $pesanan->pengiriman->kurir->user->nama ?? 'Kurir Internal' }}
                        @if($pesanan->pengiriman->kurir && $pesanan->pengiriman->kurir->id_user)
                            <a href="{{ route('pesan.show', $pesanan->pengiriman->kurir->id_user) }}" class="btn btn-sm btn-outline-success ms-2 py-0"><i class="bi bi-chat-dots"></i> Chat Kurir</a>
                        @endif
                    </div>
                    
                    <div class="col-sm-4 text-muted mt-2">No. Resi</div>
                    <div class="col-sm-8 fw-bold text-success mt-2">
                        @if($pesanan->pengiriman->no_resi)
                            {{ $pesanan->pengiriman->no_resi }}
                            <button class="btn btn-sm btn-outline-secondary ms-2 py-0" onclick="navigator.clipboard.writeText('{{ $pesanan->pengiriman->no_resi }}'); alert('Resi disalin!')"><i class="bi bi-clipboard"></i> Salin</button>
                        @else
                            <span class="badge bg-secondary">Resi Internal (Tanpa Resi)</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Produk -->
            <div class="detail-card">
                <div class="section-title">
                    <i class="bi bi-shop text-success me-2"></i> {{ $pesanan->detailPesanan->first()->produk->penjual->nama_toko ?? 'Bibitnesia' }}
                </div>
                
                @foreach ($pesanan->detailPesanan as $detail)
                    @if($detail->produk)
                    <div class="d-flex mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <img src="{{ asset('storage/' . $detail->produk->foto_produk1) }}" class="rounded border" style="width: 80px; height: 80px; object-fit: cover;">
                        <div class="ms-3 flex-grow-1">
                            <h6 class="fw-bold mb-1">{{ $detail->produk->nama_produk }}</h6>
                            <div class="text-muted small">Harga: Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</div>
                            <div class="text-muted small">Jumlah: {{ $detail->jumlah }}</div>
                        </div>
                        <div class="text-end fw-bold">
                            Rp {{ number_format($detail->harga_satuan * $detail->jumlah, 0, ',', '.') }}
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Ringkasan Pembayaran -->
            <div class="detail-card">
                <div class="section-title">Rincian Pembayaran</div>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal Produk</span>
                    <span>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span> <!-- Asumsi total harga sudah subtotal karena belum ada ongkir dinamis -->
                </div>
                <!-- TODO: Tambahkan ongkir jika ada fieldnya -->
                
                <hr>
                
                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-bold">Total Pembayaran</span>
                    <span class="fw-bold text-danger fs-5">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                </div>

                @if ($pesanan->status_pesanan == 'Menunggu Pembayaran')
                    <a href="{{ route('marketplace.invoice', $pesanan->id_pesanan) }}" class="btn btn-primary w-100">Bayar Sekarang</a>
                @elseif ($pesanan->status_pesanan == 'Pesanan dalam pengiriman')
                    <div class="text-center mt-2 small text-muted">
                        <i class="bi bi-info-circle"></i> Menunggu konfirmasi pengiriman dari kurir untuk menyelesaikan pesanan.
                    </div>
                @elseif ($pesanan->status_pesanan == 'Sampai Tujuan')
                    <form action="{{ route('marketplace.pesanan.selesai', $pesanan->id_pesanan) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold">Pesanan Diterima (Selesai)</button>
                    </form>
                    <div class="text-center mt-2 small text-muted">
                        Pastikan Anda telah menerima pesanan dalam kondisi baik sebelum klik selesai.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
    <style>
        .timeline-container {
            position: relative;
            padding-left: 30px;
            margin-bottom: 20px;
        }
        
        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }
        
        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-marker {
            position: absolute;
            left: -22px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #d1d5db;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #d1d5db;
            z-index: 2;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: -17px;
            top: 15px;
            bottom: -5px;
            width: 2px;
            background-color: #e5e7eb;
            z-index: 1;
        }

        .timeline-item.active .timeline-marker {
            background-color: #198754; /* success color */
            box-shadow: 0 0 0 2px #198754;
        }
    </style>
@endsection
