<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibitnesia - Portal Jual Beli Tanaman & Bibit</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #41A67E;
            --primary-dark: #2e7a4e;
            --primary-light: #2ecc71;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --bg-light: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Navbar Styles */
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: white !important;
            letter-spacing: -0.5px;
        }

        .navbar-brand:hover {
            color: #ecf0f1 !important;
        }

        .nav-link-custom {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-link-custom:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        .btn-login {
            background: white;
            color: var(--primary-color);
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #ecf0f1;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            height: 600px;
            overflow: hidden;
        }

        .hero-slide {
            position: relative;
            height: 600px;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(39, 174, 96, 0.8), rgba(30, 132, 73, 0.7));
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            padding: 2rem;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 1s ease;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 400;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 1.2s ease;
        }

        .btn-marketplace {
            background: white;
            color: var(--primary-color);
            padding: 1rem 3rem;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            animation: fadeInUp 1.4s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-marketplace:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 20px rgba(39, 174, 96, 0.4);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Carousel Controls */
        .carousel-control-prev,
        .carousel-control-next {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* About Section */
        .about-section {
            padding: 5rem 0;
            background: var(--bg-light);
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 3rem;
        }

        .feature-card {
            text-align: center;
            padding: 2rem 1.5rem;
            background: white;
            border-radius: 15px;
            transition: all 0.3s ease;
            height: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(39, 174, 96, 0.2);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }

        .feature-description {
            font-size: 0.95rem;
            color: var(--text-light);
            line-height: 1.6;
        }

        /* Products Section */
        .products-section {
            padding: 5rem 0;
            background: white;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            background: #e8f5e9;
        }

        .product-body {
            padding: 1.5rem;
        }

        .product-category {
            display: inline-block;
            background: var(--primary-light);
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 0.8rem;
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .product-seller {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .product-price {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .product-location {
            font-size: 0.85rem;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* CTA Banner */
        .cta-banner {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 4rem 0;
            text-align: center;
            color: white;
            margin-top: 3rem;
        }

        .cta-banner h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-banner p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .btn-explore {
            background: white;
            color: var(--primary-color);
            padding: 1rem 3rem;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-explore:hover {
            background: #ecf0f1;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1e8449, #145a32);
            color: white;
            padding: 3rem 0 1rem;
        }

        .footer-brand {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-description {
            font-size: 0.95rem;
            line-height: 1.6;
            opacity: 0.9;
            margin-bottom: 1.5rem;
        }

        .footer-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }

        .contact-item {
            display: flex;
            align-items: start;
            gap: 0.8rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .contact-item i {
            font-size: 1.2rem;
            margin-top: 0.2rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-link {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-3px);
        }

        .app-download {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .app-badge {
            height: 45px;
            transition: all 0.3s ease;
        }

        .app-badge:hover {
            transform: translateY(-3px);
            opacity: 0.9;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 2rem;
            padding-top: 1.5rem;
            text-align: center;
            opacity: 0.8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .feature-card {
                margin-bottom: 1.5rem;
            }

            .product-card {
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-flower1"></i> Bibitnesia
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link-custom" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom" href="#tentang">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom" href="#produk">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom" href="#">Marketplace</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        @auth
                            <a href="{{ route('login') }}" class="btn btn-login">
                                <i class="bi bi-people"></i> Login
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Carousel -->
    <section id="beranda" class="hero-section" style="margin-top: 76px;">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                   {{-- <div class="hero-slide" style="background-image: url('URL_GAMBAR_ANDA');"> --}}
                    <div class="hero-slide"
                        style="background-image: url('https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?w=1920');">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <h1 class="hero-title">Belanja Tanaman Lebih Mudah,<br>Cepat, dan Aman</h1>
                            <p class="hero-subtitle">Platform jual beli tanaman & bibit terpercaya di Indonesia</p>
                            <a href="#" class="btn-marketplace">
                                <i class="bi bi-cart-check"></i> Mulai Berbelanja
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <div class="hero-slide"
                        style="background-image: url('https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=1920');">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <h1 class="hero-title">Ribuan Pilihan Tanaman<br>untuk Taman Impian Anda</h1>
                            <p class="hero-subtitle">Dari tanaman hias, buah, hingga sayuran segar</p>
                            <a href="#" class="btn-marketplace">
                                <i class="bi bi-search"></i> Jelajahi Produk
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item">
                    <div class="hero-slide"
                        style="background-image: url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1920');">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <h1 class="hero-title">Jual Tanaman Anda<br>dengan Mudah</h1>
                            <p class="hero-subtitle">Bergabunglah dengan ribuan penjual tanaman di seluruh Indonesia</p>
                            <a href="#" class="btn-marketplace">
                                <i class="bi bi-shop"></i> Daftar Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="about-section">
        <div class="container">
            <h2 class="section-title">Tentang Layanan Kami</h2>
            <p class="section-subtitle">Bibitnesia adalah platform jual beli tanaman dan bibit terlengkap di
                Indonesia,<br>membantu Anda menjelajahi dunia tanaman dengan mudah dan aman</p>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h3 class="feature-title">Transaksi Realtime</h3>
                        <p class="feature-description">Proses pembelian dan penjualan tanaman secara langsung dan
                            real-time dengan notifikasi instant</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <h3 class="feature-title">Penjual Terpercaya</h3>
                        <p class="feature-description">Ribuan penjual tanaman terpercaya dari berbagai daerah di
                            seluruh Indonesia</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <h3 class="feature-title">Informasi Lengkap</h3>
                        <p class="feature-description">Detail informasi produk, cara perawatan, dan tips berkebun untuk
                            setiap tanaman</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <h3 class="feature-title">Pembayaran Digital</h3>
                        <p class="feature-description">Sistem pembayaran mudah dan aman dengan berbagai metode e-wallet
                            & transfer bank</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="produk" class="products-section">
        <div class="container">
            <h2 class="section-title">Produk Populer</h2>
            <p class="section-subtitle">Tanaman pilihan yang paling banyak diminati pelanggan kami</p>

            <div class="row g-4">
                <!-- Product 1 - Dummy (siap untuk data dinamis) -->
                @php
                    $dummyProducts = [
                        [
                            'name' => 'Monstera Deliciosa Variegata',
                            'category' => 'Tanaman Hias',
                            'price' => 'Rp 850.000',
                            'seller' => 'Green Paradise',
                            'location' => 'Jakarta Selatan',
                            'image' => 'https://images.unsplash.com/photo-1614594975525-e45190c55d0b?w=500',
                        ],
                        [
                            'name' => 'Bibit Durian Musang King',
                            'category' => 'Tanaman Buah',
                            'price' => 'Rp 275.000',
                            'seller' => 'Tani Sejahtera',
                            'location' => 'Bogor',
                            'image' => 'https://images.unsplash.com/photo-1580281780460-82d277b0e3d6?w=500',
                        ],
                        [
                            'name' => 'Paket Bibit Cabai Rawit',
                            'category' => 'Tanaman Sayur',
                            'price' => 'Rp 45.000',
                            'seller' => 'Urban Farming',
                            'location' => 'Bandung',
                            'image' => 'https://images.unsplash.com/photo-1583240197301-e3a5d82600d5?w=500',
                        ],
                        [
                            'name' => 'Aglonema Red Sumatra',
                            'category' => 'Tanaman Hias',
                            'price' => 'Rp 125.000',
                            'seller' => 'Flora Indonesia',
                            'location' => 'Surabaya',
                            'image' => 'https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?w=500',
                        ],
                        [
                            'name' => 'Bibit Mangga Kiojay Unggul',
                            'category' => 'Tanaman Buah',
                            'price' => 'Rp 150.000',
                            'seller' => 'Kebun Buah Nusantara',
                            'location' => 'Semarang',
                            'image' => 'https://images.unsplash.com/photo-1553279768-865429fa0078?w=500',
                        ],
                        [
                            'name' => 'Lidah Buaya Organik',
                            'category' => 'Tanaman Herbal',
                            'price' => 'Rp 35.000',
                            'seller' => 'Herbal Garden',
                            'location' => 'Yogyakarta',
                            'image' => 'https://images.unsplash.com/photo-1596547609652-40734502fbb3?w=500',
                        ],
                        [
                            'name' => 'Philodendron Pink Princess',
                            'category' => 'Tanaman Hias',
                            'price' => 'Rp 650.000',
                            'seller' => 'Tropical Plants',
                            'location' => 'Tangerang',
                            'image' => 'https://images.unsplash.com/photo-1459156212016-c812468e2115?w=500',
                        ],
                        [
                            'name' => 'Bibit Tomat Cherry',
                            'category' => 'Tanaman Sayur',
                            'price' => 'Rp 25.000',
                            'seller' => 'Sayur Fresh',
                            'location' => 'Bekasi',
                            'image' => 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?w=500',
                        ],
                    ];
                @endphp

                @foreach ($dummyProducts as $product)
                    <div class="col-lg-3 col-md-6">
                        <div class="product-card">
                            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="product-image">
                            <div class="product-body">
                                <span class="product-category">{{ $product['category'] }}</span>
                                <h3 class="product-title">{{ $product['name'] }}</h3>
                                <p class="product-seller">
                                    <i class="bi bi-shop"></i> {{ $product['seller'] }}
                                </p>
                                <div class="product-price">{{ $product['price'] }}</div>
                                <p class="product-location">
                                    <i class="bi bi-geo-alt-fill"></i> {{ $product['location'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @foreach ($produkPopuler as $produk)
                <div class="col-lg-3 col-md-6">
                    <div class="product-card">
                        <img src="{{ asset('storage/' . $produk->foto_produk) }}" alt="{{ $produk->nama_produk }}"
                            class="product-image">
                        <div class="product-body">
                            <span class="product-category">{{ $produk->kategori->nama_kategori ?? 'Lainnya' }}</span>
                            <h3 class="product-title">{{ $produk->nama_produk }}</h3>
                            <p class="product-seller">
                                <i class="bi bi-shop"></i> {{ $produk->user->name }}
                            </p>
                            <div class="product-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                            <p class="product-location">
                                <i class="bi bi-geo-alt-fill"></i> {{ $produk->lokasi ?? 'Indonesia' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- CTA Banner -->
            <div class="cta-banner">
                <div class="container">
                    <h2>Temukan Ribuan Tanaman Lainnya</h2>
                    <p>Jelajahi koleksi lengkap tanaman hias, buah, sayur, dan herbal dari seluruh Indonesia</p>
                    <a href="#" class="btn-explore">
                        <i class="bi bi-arrow-right-circle"></i> Jelajahi Peta Pelayanan
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <!-- Brand Column -->
                <div class="col-lg-4">
                    <div class="footer-brand">
                        <i class="bi bi-flower1"></i> Bibitnesia
                    </div>
                    <p class="footer-description">
                        Bibitnesia adalah platform pemesanan tanaman dan bibit online di Indonesia, membantu Anda
                        menjelajahi dunia tanaman dengan mudah dan aman.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link" title="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="social-link" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="social-link" title="Twitter">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="#" class="social-link" title="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <!-- Links Column -->
                <div class="col-lg-2 col-md-4">
                    <h4 class="footer-title">Kebijakan</h4>
                    <ul class="footer-links">
                        <li><a href="#"><i class="bi bi-shield-check"></i> Kebijakan Privasi</a></li>
                        <li><a href="#"><i class="bi bi-file-text"></i> Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-4">
                    <h4 class="footer-title">Layanan</h4>
                    <ul class="footer-links">
                        <li><a href="#"><i class="bi bi-question-circle"></i> Pusat Bantuan</a></li>
                        <li><a href="#"><i class="bi bi-headset"></i> Hubungi Kami</a></li>
                        <li><a href="#"><i class="bi bi-info-circle"></i> Tentang Kami</a></li>
                    </ul>
                </div>

                <!-- Contact Column -->
                <div class="col-lg-4 col-md-4">
                    <h4 class="footer-title">Kontak Kami</h4>
                    <div class="contact-item">
                        <i class="bi bi-telephone-fill"></i>
                        <div>
                            <strong>Telepon:</strong><br>
                            (021) 1234-5678
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-envelope-fill"></i>
                        <div>
                            <strong>Email:</strong><br>
                            support@bibitnesia.id
                        </div>
                    </div>

                    <!-- App Download (Optional) -->
                    <div class="app-download mt-3">
                        <p style="margin-bottom: 0.5rem;"><strong>Unduh Aplikasi</strong></p>
                        <a href="#" style="text-decoration: none;">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                                alt="Get it on Google Play" class="app-badge">
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2024 Bibitnesia.id - Platform Jual Beli Tanaman & Bibit Terpercaya</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Smooth Scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const navHeight = document.querySelector('.navbar').offsetHeight;
                    const targetPosition = target.offsetTop - navHeight;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.15)';
            } else {
                navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
            }
        });
    </script>
</body>

</html>
