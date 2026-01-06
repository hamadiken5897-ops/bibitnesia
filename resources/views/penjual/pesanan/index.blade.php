@extends('layouts.penjual.penjual')

@section('content')
    <div class="main-content">

        <!-- Header -->
        <div class="header">
            <div class="header-title">
                <h1>Pesanan Masuk</h1>
                <p>Kelola pesanan baru dari pelanggan Anda</p>
            </div>
            <div class="header-actions">
                <span class="badge-count">{{ $pesanan->count() }} Pesanan</span>
            </div>
        </div>

        @if ($pesanan->isEmpty())
            <!-- Empty State -->
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>Belum Ada Pesanan Masuk</h3>
                <p>Pesanan baru akan muncul di sini</p>
            </div>
        @else
            <!-- Orders List -->
            @foreach ($pesanan as $p)
                <div class="order-item">
                    <!-- Order Header -->
                    <div class="order-header-bar">
                        <div class="order-info-left">
                            <div class="order-icon-box">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div>
                                <h4 class="order-invoice">#{{ $p->kode_invoice }}</h4>
                                <span class="order-date">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ $p->created_at->format('d M Y, H:i') }}
                                </span>
                            </div>
                        </div>
                        <div class="order-status-badge">
                            <span class="badge badge-warning">
                                <i class="fas fa-clock"></i>
                                {{ $p->status_pesanan }}
                            </span>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="order-details-grid">
                        <div class="detail-box">
                            <span class="detail-label">ID Pesanan</span>
                            <span class="detail-value">{{ $p->id_pesanan }}</span>
                        </div>
                        <div class="detail-box">
                            <span class="detail-label">Total Harga</span>
                            <span class="detail-value text-success">Rp
                                {{ number_format($p->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="detail-box">
                            <span class="detail-label">Pembeli</span>
                            <span class="detail-value">{{ $p->user->name ?? 'Customer' }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="order-actions-bar">
                        <a href="{{ route('penjual.pesanan.show', $p->id_pesanan) }}" class="btn-action btn-detail">
                            <i class="fas fa-eye"></i>
                            Lihat Detail
                        </a>

                        <button type="button" class="btn-action btn-accept" data-bs-toggle="modal"
                            data-bs-target="#acceptModal{{ $p->id_pesanan }}">
                            <i class="fas fa-check"></i>
                            Terima Pesanan
                        </button>


                        <button type="button" class="btn-action btn-reject" data-bs-toggle="modal"
                            data-bs-target="#rejectModal{{ $p->id_pesanan }}">
                            <i class="fas fa-times"></i>
                            Tolak Pesanan
                        </button>
                    </div>

                    <!-- Expandable Detail -->
                    <div class="order-expand-detail" id="orderDetail{{ $p->id_pesanan }}" style="display: none;">
                        <div class="expand-inner">
                            <h5 class="expand-title">Item Pesanan</h5>
                            @if ($p->keranjang && $p->keranjang->isNotEmpty())
                                <div class="items-list">
                                    @foreach ($p->keranjang as $item)
                                        <div class="item-row">
                                            <img src="{{ asset('storage/' . $item->produk->foto_produk1) }}"
                                                alt="{{ $item->produk->nama_produk }}"
                                                onerror="this.src='https://via.placeholder.com/50?text=No+Image'">
                                            <div class="item-details">
                                                <strong>{{ $item->produk->nama_produk }}</strong>
                                                <small>{{ $item->qty }} x Rp
                                                    {{ number_format($item->produk->harga, 0, ',', '.') }}</small>
                                            </div>
                                            <div class="item-subtotal">
                                                Rp {{ number_format($item->qty * $item->produk->harga, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">Tidak ada item</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Accept Modal -->
                <div class="modal fade" id="acceptModal{{ $p->id_pesanan }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form method="POST" action="{{ route('penjual.pesanan.accept', $p->id_pesanan) }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Terima Pesanan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Anda yakin ingin <strong>menerima</strong> pesanan
                                        <strong>#{{ $p->kode_invoice }}</strong>?
                                    </div>

                                    <p class="mb-0 text-muted">
                                        Setelah diterima, pesanan akan diproses dan tidak dapat dibatalkan.
                                    </p>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-1"></i>
                                        Ya, Terima Pesanan
                                    </button>
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
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tolak Pesanan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-warning mb-3">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Pesanan <strong>#{{ $p->kode_invoice }}</strong> akan ditolak
                                    </div>
                                    <label class="form-label fw-bold">
                                        Alasan Penolakan <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="alasan" class="form-control" rows="4" placeholder="Jelaskan alasan penolakan pesanan..."
                                        required></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <button type="submit" class="btn btn-danger">
                                        Tolak Pesanan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif

    </div>

    <style>
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

        .order-date i {
            margin-right: 4px;
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

        /* Order Details Grid */
        .order-details-grid {
            padding: 20px 25px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
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
        }

        .btn-detail {
            background: #ecf0f1;
            color: #2c3e50;
        }

        .btn-detail:hover {
            background: #d5dbdb;
        }

        .btn-accept {
            background: #27ae60;
            color: white;
        }

        .btn-accept:hover {
            background: #229954;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(39, 174, 96, 0.3);
        }

        .btn-reject {
            background: #e74c3c;
            color: white;
        }

        .btn-reject:hover {
            background: #c0392b;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
        }

        /* Expandable Detail */
        .order-expand-detail {
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        .expand-inner {
            padding: 25px;
        }

        .expand-title {
            font-size: 15px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .items-list {
            background: white;
            border-radius: 8px;
            padding: 15px;
        }

        .item-row {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .item-row:last-child {
            border-bottom: none;
        }

        .item-row img {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
        }

        .item-details {
            flex: 1;
        }

        .item-details strong {
            display: block;
            font-size: 14px;
            color: #2c3e50;
            margin-bottom: 2px;
        }

        .item-details small {
            font-size: 12px;
            color: #7f8c8d;
        }

        .item-subtotal {
            font-size: 14px;
            font-weight: 700;
            color: #27ae60;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .order-header-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .order-details-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .order-actions-bar {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <script>
        function toggleDetail(orderId) {
            const detail = document.getElementById('orderDetail' + orderId);
            if (detail.style.display === 'none') {
                detail.style.display = 'block';
            } else {
                detail.style.display = 'none';
            }
        }
    </script>
@endsection
