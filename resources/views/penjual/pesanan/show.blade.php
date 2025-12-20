@extends('layouts.penjual.penjual')

@section('content')
    <div class="main-content">

        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('penjual.pesanan.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Daftar Pesanan
            </a>
        </div>

        <!-- Header -->
        <div class="header">
            <div class="header-title">
                <h1>Detail Pesanan #{{ $pesanan->kode_invoice }}</h1>
                <p>Informasi lengkap pesanan dari pelanggan</p>
            </div>
            <div class="header-actions">
                <span
                    class="badge-status {{ $pesanan->status_pesanan === 'Pesanan sedang diproses' ? 'badge-warning' : 'badge-success' }}">
                    <i class="fas fa-clock"></i>
                    {{ $pesanan->status_pesanan }}
                </span>
            </div>
        </div>

        <div class="detail-container">
            <!-- Left Column: Order Info -->
            <div class="detail-left">

                <!-- Order Summary Card -->
                <div class="info-card">
                    <div class="card-header">
                        <i class="fas fa-file-invoice"></i>
                        <h3>Ringkasan Pesanan</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <span class="info-label">ID Pesanan</span>
                            <span class="info-value">{{ $pesanan->id_pesanan }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Kode Invoice</span>
                            <span class="info-value">#{{ $pesanan->kode_invoice }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tanggal Pesanan</span>
                            <span class="info-value">{{ $pesanan->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Total Harga</span>
                            <span class="info-value text-success fw-bold">
                                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Customer Info Card -->
                <div class="info-card">
                    <div class="card-header">
                        <i class="fas fa-user"></i>
                        <h3>Informasi Pembeli</h3>
                    </div>
                    <div class="card-body">
                        <div class="customer-profile">
                            <div class="customer-avatar">
                                @if ($pesanan->user->profile_image)
                                    <img src="{{ asset('storage/' . $pesanan->user->profile_image) }}"
                                        alt="{{ $pesanan->user->name }}">
                                @else
                                    <span>{{ strtoupper(substr($pesanan->user->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="customer-info">
                                <h4>{{ $pesanan->user->name }}</h4>
                                <p class="customer-email">{{ $pesanan->user->email }}</p>
                            </div>
                        </div>

                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-user"></i>
                                Nama Lengkap
                            </span>
                            <span class="info-value">{{ $pesanan->user->nama }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-phone"></i>
                                No. Telepon
                            </span>
                            <span class="info-value">{{ $pesanan->user->no_telepon ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Alamat Pengiriman
                            </span>
                            <span class="info-value text-wrap">{{ $pesanan->user->alamat ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-user-tag"></i>
                                Status
                            </span>
                            <span class="info-value">
                                <span class="badge badge-role">{{ ucfirst($pesanan->user->role) }}</span>
                            </span>
                        </div>
                    </div>
                </div>


                <!-- Action Buttons -->
                @if ($pesanan->status_pesanan === 'Pesanan sedang diproses')
                    <div class="action-card mt-4">
                        <form action="{{ route('penjual.pesanan.accept', $pesanan->id_pesanan) }}" method="POST"
                            class="mb-3" onsubmit="return confirm('Apakah Anda yakin ingin menerima pesanan ini?')">
                            @csrf
                            <button type="submit" class="btn btn-accept w-100">
                                <i class="fas fa-check-circle me-2"></i>
                                Terima Pesanan
                            </button>
                        </form>

                        <button type="button" class="btn btn-reject w-100" data-bs-toggle="modal"
                            data-bs-target="#rejectModal">
                            <i class="fas fa-times-circle me-2"></i>
                            Tolak Pesanan
                        </button>
                    </div>
                @endif

            </div>

            <!-- Right Column: Order Items -->
            <div class="detail-right">
                <div class="items-card">
                    <div class="card-header">
                        <i class="fas fa-shopping-cart"></i>
                        @php
                            $items = $pesanan->detailPesanan ?? ($pesanan->keranjang ?? collect([]));
                            $itemCount = $items->count();
                        @endphp
                        <h3>Item Pesanan ({{ $itemCount }} Item)</h3>
                    </div>
                    <div class="card-body">
                        @if ($items->isNotEmpty())
                            <div class="items-list">
                                @foreach ($items as $detail)
                                    @php
                                        // Support both detailPesanan and keranjang
                                        $produk = $detail->produk ?? null;
                                        $jumlah = $detail->jumlah ?? ($detail->qty ?? 0);
                                        $hargaSatuan = $detail->harga_satuan ?? ($produk->harga ?? 0);
                                    @endphp

                                    @if ($produk)
                                        <div class="product-item">
                                            <div class="product-image">
                                                <img src="{{ asset('storage/' . $produk->foto_produk1) }}"
                                                    alt="{{ $produk->nama_produk }}"
                                                    onerror="this.src='https://via.placeholder.com/80?text=No+Image'">
                                            </div>
                                            <div class="product-details">
                                                <h5>{{ $produk->nama_produk }}</h5>
                                                <p class="product-id">ID: {{ $produk->id_produk }}</p>
                                                <div class="product-meta">
                                                    <span class="qty">
                                                        <i class="fas fa-cubes"></i>
                                                        Qty: {{ $jumlah }}
                                                    </span>
                                                    <span class="price">
                                                        Rp {{ number_format($hargaSatuan, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="product-subtotal">
                                                <span class="subtotal-label">Subtotal</span>
                                                <span class="subtotal-value">
                                                    Rp {{ number_format($jumlah * $hargaSatuan, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Total Section -->
                            <div class="total-section">
                                <div class="total-row">
                                    <span>Subtotal Produk</span>
                                    <span>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                                </div>
                                @php
                                    $totalOngkir = $items->sum('ongkir');
                                @endphp
                                <div class="total-row">
                                    <span>Ongkos Kirim</span>
                                    <span>{{ $totalOngkir > 0 ? 'Rp ' . number_format($totalOngkir, 0, ',', '.') : 'Gratis' }}</span>
                                </div>
                                <div class="total-row total-final">
                                    <span>Total Pembayaran</span>
                                    <span>Rp
                                        {{ number_format($pesanan->total_harga + $totalOngkir, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="empty-items">
                                <i class="fas fa-box-open"></i>
                                <p>Tidak ada item dalam pesanan ini</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('penjual.pesanan.reject', $pesanan->id_pesanan) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak Pesanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Pesanan <strong>#{{ $pesanan->kode_invoice }}</strong> akan ditolak
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

    <style>
        /* Back Button */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: white;
            color: #2c3e50;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #f8f9fa;
            color: #27ae60;
            transform: translateX(-3px);
        }

        /* Badge Status */
        .badge-status {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-success {
            background: #d1e7dd;
            color: #0f5132;
        }

        /* Detail Container */
        .detail-container {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 25px;
            margin-top: 25px;
        }

        /* Info Card */
        .info-card,
        .items-card,
        .action-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            border: 1px solid #f1f5f9;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-header i {
            font-size: 20px;
            color: #27ae60;
        }

        .card-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #2c3e50;
        }

        .card-body {
            padding: 20px;
        }

        /* Info Row */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 13px;
            color: #7f8c8d;
            font-weight: 500;
        }

        .info-value {
            font-size: 14px;
            color: #2c3e50;
            font-weight: 600;
            text-align: right;
        }

        /* Customer Profile */
        .customer-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f1f5f9;
            margin-bottom: 15px;
        }

        .customer-avatar {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            background: #27ae60;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .customer-avatar span {
            font-size: 28px;
            font-weight: 700;
            color: white;
        }

        .customer-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .customer-info h4 {
            font-size: 16px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 4px 0;
        }

        .customer-info .customer-email {
            font-size: 13px;
            color: #7f8c8d;
            margin: 0;
            word-break: break-all;
        }

        .badge-role {
            background: #e8f5e9;
            color: #27ae60;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .text-wrap {
            word-wrap: break-word;
            white-space: normal;
            max-width: 200px;
        }

        /* Product Item */
        .items-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .product-item {
            display: flex;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            align-items: center;
        }

        .product-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-details {
            flex: 1;
        }

        .product-details h5 {
            font-size: 15px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 4px 0;
        }

        .product-id {
            font-size: 12px;
            color: #7f8c8d;
            margin: 0 0 8px 0;
        }

        .product-meta {
            display: flex;
            gap: 15px;
            font-size: 13px;
        }

        .product-meta .qty {
            color: #7f8c8d;
        }

        .product-meta .price {
            color: #27ae60;
            font-weight: 600;
        }

        .product-subtotal {
            text-align: right;
        }

        .subtotal-label {
            display: block;
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 4px;
        }

        .subtotal-value {
            font-size: 16px;
            font-weight: 700;
            color: #27ae60;
        }

        /* Total Section */
        .total-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
            color: #2c3e50;
        }

        .total-final {
            border-top: 2px solid #e9ecef;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 16px;
            font-weight: 700;
            color: #27ae60;
        }

        /* Action Buttons */
        .action-card {
            padding: 20px;
        }

        .btn-action-full {
            width: 100%;
            padding: 14px;
            border-radius: 8px;
            border: none;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .btn-action-full:last-child {
            margin-bottom: 0;
        }

        .btn-accept {
            background: #27ae60;
            color: white;
        }

        .btn-accept:hover {
            background: #229954;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
        }

        .btn-reject {
            background: #e74c3c;
            color: white;
        }

        .btn-reject:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        }

        /* Empty State */
        .empty-items {
            text-align: center;
            padding: 40px 20px;
            color: #94a3b8;
        }

        .empty-items i {
            font-size: 48px;
            margin-bottom: 15px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .detail-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
