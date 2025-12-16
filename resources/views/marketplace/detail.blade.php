@extends('layouts.marketplace.main')

@section('content')
    <div class="container">
        <div class="product-detail-wrapper">
            <div class="product-detail-grid">

                <!-- Product Images -->
                <div class="product-images">
                    <div id="mainCarousel" class="carousel slide main-carousel" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @if ($produk->foto_produk1)
                                <div class="carousel-item active">
                                    <img src="{{ asset('storage/' . $produk->foto_produk1) }}">
                                </div>
                            @endif
                            @if ($produk->foto_produk2)
                                <div class="carousel-item">
                                    <img src="{{ asset('storage/' . $produk->foto_produk2) }}">
                                </div>
                            @endif
                            @if ($produk->foto_produk3)
                                <div class="carousel-item">
                                    <img src="{{ asset('storage/' . $produk->foto_produk3) }}">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="thumbnail-grid">
                        @if ($produk->foto_produk1)
                            <div class="thumbnail active" data-bs-target="#mainCarousel" data-bs-slide-to="0">
                                <img src="{{ asset('storage/' . $produk->foto_produk1) }}">
                            </div>
                        @endif
                        @if ($produk->foto_produk2)
                            <div class="thumbnail" data-bs-target="#mainCarousel" data-bs-slide-to="1">
                                <img src="{{ asset('storage/' . $produk->foto_produk2) }}">
                            </div>
                        @endif
                        @if ($produk->foto_produk3)
                            <div class="thumbnail" data-bs-target="#mainCarousel" data-bs-slide-to="2">
                                <img src="{{ asset('storage/' . $produk->foto_produk3) }}">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Info -->
                <div class="product-info">
                    <h1>{{ $produk->nama_produk }}</h1>
                    <div class="product-price">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </div>

                    <div class="product-meta">
                        <div class="meta-item">
                            <i class="fas fa-box"></i> Stok: {{ $produk->stok }}
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-tag"></i>
                            {{ ucfirst(str_replace('_', ' ', $produk->kategori)) }}
                        </div>
                    </div>

                    <div class="seller-info">
                        <div class="seller-avatar">
                            @if (!empty($produk->penjual->user->profile_image))
                                <img src="{{ asset('storage/' . $produk->penjual->user->profile_image) }}"
                                    alt="{{ $produk->penjual->nama_penjual }}">
                            @else
                                {{ strtoupper(substr($produk->penjual->nama_penjual ?? 'P', 0, 1)) }}
                            @endif
                        </div>>

                        <div class="seller-details">
                            <h3>{{ $produk->penjual->nama_penjual ?? 'Penjual' }}</h3>

                            <p>
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $produk->penjual->provinsi->nama_provinsi ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="product-description">
                        <h5>Deskripsi Produk</h5>
                        <p>{{ $produk->deskripsi }}</p>
                    </div>

                    <div class="action-buttons">
                        <button class="btn-add-cart">
                            <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                        </button>
                        <button class="btn-buy-now">
                            <i class="fas fa-bolt"></i> Beli Sekarang
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
