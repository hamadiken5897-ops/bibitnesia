@extends('layouts.penjual.penjual')

@section('content')
<style>
    /* Tabs for Seller Order */
    .nav-tabs-seller {
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        background: #fff;
        padding: 0;
        margin-bottom: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    .nav-tabs-seller .nav-item {
        flex-grow: 1;
        text-align: center;
    }
    .nav-tabs-seller .nav-link {
        color: #555;
        border: none;
        padding: 15px 10px;
        font-weight: 600;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
        text-decoration: none;
        display: block;
    }
    .nav-tabs-seller .nav-link:hover {
        color: #27ae60;
        background: #f8f9fa;
    }
    .nav-tabs-seller .nav-link.active {
        color: #27ae60;
        border-bottom: 3px solid #27ae60;
        background: #f4faf6;
    }

    /* Header Badge */
    .badge-count {
        background: #e8f5e9;
        color: #27ae60;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
    }

    /* Empty State */
    .empty-state {
        background: white;
        padding: 60px 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .empty-state i {
        font-size: 64px;
        color: #cbd5e1;
        margin-bottom: 20px;
    }
    .empty-state h3 {
        font-size: 20px;
        color: #64748b;
        margin-bottom: 8px;
    }
    .empty-state p {
        color: #94a3b8;
        font-size: 14px;
    }

    /* Order Item */
    .order-item {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
        overflow: hidden;
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }
    .order-item:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    /* Order Header Bar */
    .order-header-bar {
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f1f5f9;
    }
    .order-info-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .order-icon-box {
        width: 48px;
        height: 48px;
        background: #e8f5e9;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #27ae60;
        font-size: 20px;
    }
    .order-invoice {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 4px 0;
    }
    .order-date {
        font-size: 13px;
        color: #7f8c8d;
    }
    .order-status-badge .badge {
        padding: 8px 14px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 6px;
    }
    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }
    .badge-info {
        background: #d1ecf1;
        color: #0c5460;
    }
    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    /* Order Details Grid */
    .order-details-grid {
        padding: 20px 25px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        background: #f8f9fa;
    }
    .detail-box {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .detail-label {
        font-size: 12px;
        color: #7f8c8d;
        font-weight: 500;
    }
    .detail-value {
        font-size: 14px;
        color: #2c3e50;
        font-weight: 600;
    }
    .text-success {
        color: #27ae60 !important;
    }

    /* Action Buttons Bar */
    .order-actions-bar {
        padding: 20px 25px;
        display: flex;
        gap: 10px;
        border-top: 1px solid #f1f5f9;
        justify-content: flex-end;
    }
    .btn-action {
        padding: 10px 18px;
        border-radius: 8px;
        border: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-detail {
        background: #ecf0f1;
        color: #2c3e50;
    }
    .btn-detail:hover {
        background: #d5dbdb;
        color: #2c3e50;
    }
    .btn-accept {
        background: #27ae60;
        color: white;
    }
    .btn-accept:hover {
        background: #229954;
        color: white;
    }
    .btn-reject {
        background: #e74c3c;
        color: white;
    }
    .btn-reject:hover {
        background: #c0392b;
        color: white;
    }
    .btn-ship {
        background: #3498db;
        color: white;
    }
    .btn-ship:hover {
        background: #2980b9;
        color: white;
    }
</style>

<div class="main-content">
    <!-- Header -->
    <div class="header mb-4 d-flex justify-content-between align-items-center">
        <div class="header-title">
            <h2 class="fw-bold">Pesanan Masuk</h2>
            <p class="text-muted">Kelola pesanan dari pelanggan Anda</p>
        </div>
        <div class="header-actions">
            <span class="badge-count">{{ $pesanan->count() }} Pesanan</span>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs-seller">
        <li class="nav-item">
            <a class="nav-link {{ $status == 'baru' ? 'active' : '' }}" href="{{ route('penjual.pesanan.index', ['status' => 'baru']) }}">Pesanan Baru</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'perlu-dikirim' ? 'active' : '' }}" href="{{ route('penjual.pesanan.index', ['status' => 'perlu-dikirim']) }}">Perlu Dikirim</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'dikirim' ? 'active' : '' }}" href="{{ route('penjual.pesanan.index', ['status' => 'dikirim']) }}">Dikirim</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'selesai' ? 'active' : '' }}" href="{{ route('penjual.pesanan.index', ['status' => 'selesai']) }}">Selesai</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $status == 'dibatalkan' ? 'active' : '' }}" href="{{ route('penjual.pesanan.index', ['status' => 'dibatalkan']) }}">Dibatalkan</a>
        </li>
    </ul>

    @if(in_array($status, ['selesai', 'dibatalkan']))
        <div class="alert alert-info mt-3 mb-0 border-0 shadow-sm" style="background-color: #e8f4fd; color: #0c5460;">
            <i class="bi bi-info-circle-fill me-2"></i> Tab ini hanya menampilkan pesanan <strong>hari ini</strong>. Untuk seluruh riwayat, silakan cek menu <strong>Riwayat Pesanan</strong> di sidebar.
        </div>
    @endif

    @if ($pesanan->isEmpty())
        <!-- Empty State -->
        <div class="empty-state mt-4">
            <i class="bi bi-inbox text-muted"></i>
            <h3>Belum Ada Pesanan</h3>
            <p>Tidak ada pesanan untuk status ini saat ini.</p>
        </div>
    @else
        <!-- Orders List -->
        @foreach ($pesanan as $p)
            <div class="order-item mt-4">
                <!-- Order Header -->
                <div class="order-header-bar">
                    <div class="order-info-left">
                        <div class="order-icon-box">
                            <i class="bi bi-bag"></i>
                        </div>
                        <div>
                            <h4 class="order-invoice">#{{ $p->id_pesanan }}</h4>
                            <span class="order-date">
                                <i class="bi bi-calendar"></i>
                                {{ $p->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                    </div>
                    <div class="order-status-badge">
                        @if($p->status_pesanan == 'Menunggu konfirmasi penjual')
                            <span class="badge badge-warning">Pesanan Baru</span>
                        @elseif($p->status_pesanan == 'Pesanan sedang diproses')
                            <span class="badge badge-info">Perlu Dikirim</span>
                        @elseif($p->status_pesanan == 'Pesanan dalam pengiriman')
                            <span class="badge badge-success">Dikirim</span>
                        @elseif($p->status_pesanan == 'Pesanan selesai')
                            <span class="badge badge-success">Selesai</span>
                        @else
                            <span class="badge badge-secondary">{{ $p->status_pesanan }}</span>
                        @endif
                    </div>
                </div>

                <!-- Order Details -->
                <div class="order-details-grid">
                    <div class="detail-box">
                        <span class="detail-label">Pembeli</span>
                        <span class="detail-value">{{ $p->user->nama ?? 'Customer' }}</span>
                    </div>
                    <div class="detail-box">
                        <span class="detail-label">Total Harga</span>
                        <span class="detail-value text-success">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="detail-box">
                        <span class="detail-label">Jumlah Item</span>
                        <span class="detail-value">{{ $p->detailPesanan->sum('jumlah') }} Barang</span>
                    </div>
                    <div class="detail-box">
                        <span class="detail-label">Kurir & Resi</span>
                        <span class="detail-value">
                            @if($p->pengiriman)
                                {{ strtoupper($p->pengiriman->kurir->user->nama ?? 'KURIR INTERNAL') }} - {{ $p->pengiriman->no_resi ?? 'Menunggu Resi' }}
                            @else
                                <span class="text-muted">Belum ada</span>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="order-actions-bar">
                    <a href="{{ route('penjual.pesanan.show', $p->id_pesanan) }}" class="btn-action btn-detail">
                        <i class="bi bi-eye"></i> Lihat Detail
                    </a>

                    @if ($p->status_pesanan == 'Menunggu konfirmasi penjual')
                        <button type="button" class="btn-action btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $p->id_pesanan }}">
                            <i class="bi bi-x-circle"></i> Tolak
                        </button>
                        <button type="button" class="btn-action btn-accept" data-bs-toggle="modal" data-bs-target="#acceptModal{{ $p->id_pesanan }}">
                            <i class="bi bi-check-circle"></i> Terima Pesanan
                        </button>
                    @elseif ($p->status_pesanan == 'Pesanan sedang diproses')
                        <a href="{{ route('penjual.pesanan.kurir', $p->id_pesanan) }}" class="btn-action btn-ship">
                            <i class="bi bi-search"></i> Cari Kurir
                        </a>
                    @endif
                </div>

                <!-- Accept Modal -->
                @if ($p->status_pesanan == 'Menunggu konfirmasi penjual')
                <div class="modal fade" id="acceptModal{{ $p->id_pesanan }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form method="POST" action="{{ route('penjual.pesanan.accept', $p->id_pesanan) }}">
                            @csrf
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header border-bottom-0">
                                    <h5 class="modal-title fw-bold">Terima Pesanan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body py-4">
                                    <div class="text-center mb-4">
                                        <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                                    </div>
                                    <p class="text-center">Anda yakin ingin menerima pesanan <strong>#{{ $p->id_pesanan }}</strong> dari <strong>{{ $p->user->nama ?? 'Customer' }}</strong>?</p>
                                    <p class="text-center text-muted small">Setelah diterima, pesanan akan masuk ke tab "Perlu Dikirim".</p>
                                </div>
                                <div class="modal-footer border-top-0 d-flex justify-content-center">
                                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success px-4">Terima Pesanan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Reject Modal -->
                <div class="modal fade" id="rejectModal{{ $p->id_pesanan }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form method="POST" action="{{ route('penjual.pesanan.reject', $p->id_pesanan) }}">
                            @csrf
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header border-bottom-0">
                                    <h5 class="modal-title fw-bold">Tolak Pesanan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body py-3">
                                    <div class="alert alert-warning">
                                        Pesanan <strong>#{{ $p->id_pesanan }}</strong> akan ditolak.
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                                        <textarea name="alasan" class="form-control" rows="3" placeholder="Masukkan alasan penolakan untuk pembeli..." required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Tolak Pesanan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        @endforeach
    @endif
</div>
@endsection
