<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibitnesia Marketplace - Belanja Tanaman & Bibit</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('marketplace_as/css/marketplace.css') }}">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <i class="fas fa-seedling"></i>
            <span>Bibitnesia</span>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('marketplace.index') }}" class="active">
                    <i class="fas fa-home"></i>
                    <span>Beranda</span>
                </a>
            </li>
            <li>
                <a href="{{ route('keranjang.index') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Keranjang</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-heart"></i>
                    <span>Favorit</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-box"></i>
                    <span>Pesanan Saya</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-history"></i>
                    <span>Riwayat</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-store"></i>
                    <span>Jadi Penjual</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-question-circle"></i>
                    <span>Bantuan</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <div class="header-title">
                <h1>Marketplace Bibitnesia</h1>
                <p>Temukan tanaman & bibit berkualitas dari penjual terpercaya</p>
            </div>
            <div class="header-actions">
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <button class="profile-btn">
                    {{ auth()->user()->name ?? 'U' }}
                </button>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div class="search-filter-section">
            <form action="{{ route('marketplace.index') }}" method="GET">
                <div class="search-bar">
                    <input type="text" name="search" placeholder="Cari tanaman, bibit, atau produk lainnya..."
                        value="{{ request('search') }}">
                    <button type="submit">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>

                <div class="filter-row">
                    <div class="filter-item">
                        <label><i class="fas fa-tag"></i> Harga Minimum</label>
                        <input type="number" name="harga_min" placeholder="Rp 0" value="{{ request('harga_min') }}">
                    </div>
                    <div class="filter-item">
                        <label><i class="fas fa-tag"></i> Harga Maximum</label>
                        <input type="number" name="harga_max" placeholder="Rp 1.000.000"
                            value="{{ request('harga_max') }}">
                    </div>
                    <div class="filter-item">
                        <label><i class="fas fa-map-marker-alt"></i> Lokasi</label>
                        <select name="lokasi">
                            <option value="">Semua Lokasi</option>
                            <option value="terdekat">Terdekat</option>
                            <option value="jakarta">Jakarta</option>
                            <option value="bandung">Bandung</option>
                            <option value="surabaya">Surabaya</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label><i class="fas fa-sort"></i> Urutkan</label>
                        <select name="sort" onchange="this.form.submit()">
                            <option value="">Terbaru</option>
                            <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Termurah
                            </option>
                            <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Termahal
                            </option>
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru
                            </option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Category Navbar -->
        <div class="category-navbar">
            <h3 class="category-title">Kategori Produk</h3>

            <div class="category-grid">

                <!-- Tanaman Hias (Merah) -->
                <div class="category-card cat-hias"
                    onclick="window.location.href='{{ route('marketplace.index') }}?kategori=Tanaman_hias'">
                    <img src="https://images.unsplash.com/photo-1466781783364-36c955e42a7f?w=500&h=300&fit=crop"
                        alt="Tanaman Hias">
                    <div class="category-overlay">
                        <div class="category-icon">🌺</div>
                        <div class="category-name">Tanaman Hias</div>
                    </div>
                </div>

                <!-- Buah-buahan (Kuning) -->
                <div class="category-card cat-buah"
                    onclick="window.location.href='{{ route('marketplace.index') }}?kategori=buah'">
                    <img src="https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=500&h=300&fit=crop"
                        alt="Buah-buahan">
                    <div class="category-overlay">
                        <div class="category-icon">🍎</div>
                        <div class="category-name">Buah-buahan</div>
                    </div>
                </div>

                <!-- Sayuran (Hijau) -->
                <div class="category-card cat-sayur"
                    onclick="window.location.href='{{ route('marketplace.index') }}?kategori=sayur'">
                    <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?w=500&h=300&fit=crop"
                        alt="Sayuran">
                    <div class="category-overlay">
                        <div class="category-icon">🥬</div>
                        <div class="category-name">Sayuran</div>
                    </div>
                </div>

                <!-- Lainnya (Biru) -->
                <div class="category-card cat-lainnya"
                    onclick="window.location.href='{{ route('marketplace.index') }}'">
                    <img src="https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?w=500&h=300&fit=crop"
                        alt="Lainnya">
                    <div class="category-overlay">
                        <div class="category-icon">🌿</div>
                        <div class="category-name">Lainnya</div>
                    </div>
                </div>

            </div>
        </div>


        <!-- Products Section -->
        <div class="products-section">
            <div class="section-header">
                <h2 class="section-title">Produk Pilihan</h2>
                <span style="color: #7f8c8d;">{{ isset($produk) ? $produk->total() : 0 }} Produk Ditemukan</span>
            </div>

            <div class="products-grid">
                <!-- Produk Statis 1 -->
                <div class="product-card">
                    <div class="product-image-container">
                        <div id="carousel1" class="carousel slide product-carousel" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="https://images.unsplash.com/photo-1459411552884-841db9b3cc2a?w=400&h=300&fit=crop"
                                        alt="Monstera Deliciosa">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://images.unsplash.com/photo-1545241047-6083a3684587?w=400&h=300&fit=crop"
                                        alt="Monstera Deliciosa">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel1"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel1"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                        <span class="product-badge">BEST SELLER</span>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Monstera Deliciosa - Tanaman Hias Indoor Premium</h3>
                        <div class="product-price">Rp 250.000</div>
                        <div class="product-seller">
                            <i class="fas fa-store"></i>
                            <span>Green Paradise Store</span>
                        </div>
                    </div>
                    <div class="product-footer">
                        <div class="product-stock">
                            <i class="fas fa-box"></i> Stok: 45
                        </div>
                        <div class="product-location">
                            <i class="fas fa-map-marker-alt"></i> Jakarta
                        </div>
                    </div>
                </div>

                <!-- Produk Statis 2 -->
                <div class="product-card">
                    <div class="product-image-container">
                        <div id="carousel2" class="carousel slide product-carousel" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="https://images.unsplash.com/photo-1600411833402-f5a5e0a53a0c?w=400&h=300&fit=crop"
                                        alt="Bibit Tomat Cherry">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://images.unsplash.com/photo-1592841200221-a6898f307baa?w=400&h=300&fit=crop"
                                        alt="Bibit Tomat Cherry">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel2"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel2"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                        <span class="product-badge">PROMO</span>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Bibit Tomat Cherry Organik Siap Tanam</h3>
                        <div class="product-price">Rp 35.000</div>
                        <div class="product-seller">
                            <i class="fas fa-store"></i>
                            <span>Berkebun Sehat</span>
                        </div>
                    </div>
                    <div class="product-footer">
                        <div class="product-stock">
                            <i class="fas fa-box"></i> Stok: 120
                        </div>
                        <div class="product-location">
                            <i class="fas fa-map-marker-alt"></i> Bandung
                        </div>
                    </div>
                </div>

                <!-- Produk Statis 3 -->
                <div class="product-card">
                    <div class="product-image-container">
                        <div id="carousel3" class="carousel slide product-carousel" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="https://images.unsplash.com/photo-1464226184884-fa280b87c399?w=400&h=300&fit=crop"
                                        alt="Bibit Mangga">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://images.unsplash.com/photo-1605027990121-cbae9af22e32?w=400&h=300&fit=crop"
                                        alt="Bibit Mangga">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel3"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel3"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                        <span class="product-badge">NEW</span>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Bibit Mangga Gedong Gincu Unggul</h3>
                        <div class="product-price">Rp 150.000</div>
                        <div class="product-seller">
                            <i class="fas fa-store"></i>
                            <span>Tani Makmur</span>
                        </div>
                    </div>
                    <div class="product-footer">
                        <div class="product-stock">
                            <i class="fas fa-box"></i> Stok: 30
                        </div>
                        <div class="product-location">
                            <i class="fas fa-map-marker-alt"></i> Surabaya
                        </div>
                    </div>
                </div>

                <!-- Produk Statis 4 -->
                <div class="product-card">
                    <div class="product-image-container">
                        <div id="carousel4" class="carousel slide product-carousel" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="https://images.unsplash.com/photo-1509937528035-ad76254b0356?w=400&h=300&fit=crop"
                                        alt="Anthurium">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://images.unsplash.com/photo-1597868165956-03a6827955b1?w=400&h=300&fit=crop"
                                        alt="Anthurium">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel4"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel4"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">Anthurium Red - Tanaman Hias Cantik</h3>
                        <div class="product-price">Rp 180.000</div>
                        <div class="product-seller">
                            <i class="fas fa-store"></i>
                            <span>Flora Indah</span>
                        </div>
                    </div>
                    <div class="product-footer">
                        <div class="product-stock">
                            <i class="fas fa-box"></i> Stok: 25
                        </div>
                        <div class="product-location">
                            <i class="fas fa-map-marker-alt"></i> Jakarta
                        </div>
                    </div>
                </div>

                <!-- Produk dari Database -->
                @if (isset($produk) && $produk->count() > 0)
                    @foreach ($produk as $index => $item)
                        <div class="product-card">
                            <div class="product-image-container">
                                <div id="carouselDB{{ $index }}" class="carousel slide product-carousel"
                                    data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @if ($item->gambar_1)
                                            <div class="carousel-item active">
                                                <img src="{{ asset('storage/' . $item->gambar_1) }}"
                                                    alt="{{ $item->nama_produk }}">
                                            </div>
                                        @endif
                                        @if ($item->gambar_2)
                                            <div class="carousel-item {{ !$item->gambar_1 ? 'active' : '' }}">
                                                <img src="{{ asset('storage/' . $item->gambar_2) }}"
                                                    alt="{{ $item->nama_produk }}">
                                            </div>
                                        @endif
                                        @if ($item->gambar_3)
                                            <div
                                                class="carousel-item {{ !$item->gambar_1 && !$item->gambar_2 ? 'active' : '' }}">
                                                <img src="{{ asset('storage/' . $item->gambar_3) }}"
                                                    alt="{{ $item->nama_produk }}">
                                            </div>
                                        @endif
                                        @if (!$item->gambar_1 && !$item->gambar_2 && !$item->gambar_3)
                                            <div class="carousel-item active">
                                                <img src="https://via.placeholder.com/400x300?text=No+Image"
                                                    alt="No Image">
                                            </div>
                                        @endif
                                    </div>
                                    @if ($item->gambar_1 && ($item->gambar_2 || $item->gambar_3))
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#carouselDB{{ $index }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#carouselDB{{ $index }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </button>
                                    @endif
                                </div>
                                @if ($item->status == 'aktif')
                                    <span class="product-badge">TERSEDIA</span>
                                @endif
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">{{ $item->nama_produk }}</h3>
                                <div class="product-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                                <div class="product-seller">
                                    <i class="fas fa-store"></i>
                                    <span>{{ $item->nama_penjual }}</span>
                                </div>
                            </div>
                            <div class="product-footer">
                                <div class="product-stock">
                                    <i class="fas fa-box"></i> Stok: {{ $item->stok }}
                                </div>
                                <div class="product-location">
                                    <i class="fas fa-map-marker-alt"></i> {{ $item->lokasi_penjual }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Pagination -->
            @if (isset($produk) && $produk->hasPages())
                <div class="pagination-wrapper">
                    {{ $produk->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('marketplace_as/js/marketplace.js') }}"></script>

</body>

</html>
