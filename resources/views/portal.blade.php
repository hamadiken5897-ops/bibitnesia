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

     <!-- sweetalert3 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('portal/css/portal.css') }}">
</head>

<body>
    <!-- Navbar -->

    {{-- <img src="{{ asset('portal/images/logo/LogoBibitnesia-White.png') }}" alt="Bibitnesia"> --}}

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <div class="header-logo" href="/">
                <img src="{{ asset('portal/images/logo/LogoBibitnesia-White.png') }}" alt="Bibitnesia">
            </div>
            <div class ="navbar-brand" href="/">
                Bibitnesia
            </div>
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
                        <a class="nav-link-custom" href="{{ route('marketplace.index') }}">Marketplace</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        @auth
                            @php
                                $notif = \App\Models\PengajuanMitra::where('id_user', auth()->user()->id_user)
                                    ->where('is_read_user', false)
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                            @endphp

                            @if ($notif)
                        <li class="nav-item me-3">
                            <a href="#" class="nav-link-custom text-warning" data-status="{{ $notif->status }}"
                                data-note="{{ $notif->catatan_admin }}" data-id="{{ $notif->id_pengajuan }}"
                                onclick="showNotif(this)">
                                <i class="bi bi-bell-fill"></i> <span class="badge bg-danger">1</span>
                            </a>
                        </li>
                        @endif
                    @endauth
                    </li>
                    <li class="nav-item ms-lg-3">
                        @auth
                            <a href="{{ route('profile.show') }}" class="btn btn-login d-flex align-items-center">
                                <i class="bi bi-person-circle fs-5 me-1"></i> Profil
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-login">
                                <i class="bi bi-box-arrow-in-right"></i> LOGIN
                            </a>
                        @endauth

                    </li>

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
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
            </div>

            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <div class="hero-slide"
                        style="background-image: url('{{ asset('portal/images/hero/hero-1.jpg') }}');">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <h1 class="hero-title">Belanja Tanaman Lebih Mudah,<br>Cepat, dan Aman</h1>
                            <p class="hero-subtitle">Platform jual beli tanaman & bibit terpercaya di Indonesia</p>
                            <a href="{{ route('marketplace.index') }}" class="btn-marketplace">
                                <i class="bi bi-cart-check"></i> Mulai Berbelanja
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <div class="hero-slide"
                        style="background-image: url('{{ asset('portal/images/hero/hero-2.jpg') }}');">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <h1 class="hero-title">Ribuan Pilihan Tanaman<br>untuk Taman Impian Anda</h1>
                            <p class="hero-subtitle">Dari tanaman hias, buah, hingga sayuran segar</p>
                            <a href="{{ route('marketplace.index') }}" class="btn-marketplace">
                                <i class="bi bi-search"></i> Jelajahi Produk
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item">
                    <div class="hero-slide"
                        style="background-image: url('{{ asset('portal/images/hero/hero-3.png') }}');">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <h1 class="hero-title">Jual Tanaman Anda<br>dengan Mudah</h1>
                            <p class="hero-subtitle">Bergabunglah dengan ribuan penjual tanaman di seluruh Indonesia
                            </p>

                            @guest
                                <!-- User belum login -->
                                <a href="{{ route('login') }}" class="btn-marketplace">
                                    <i class="bi bi-shop"></i> Daftar Sekarang
                                </a>
                            @endguest

                            @auth
                                <!-- User sudah login -->
                                <a href="{{ route('daftar.penjual') }}" class="btn-marketplace">
                                    <i class="bi bi-shop"></i> Daftar Penjual
                                </a>
                            @endauth

                        </div>
                    </div>
                </div>

                <!-- Slide 4 -->
                <div class="carousel-item">
                    <div class="hero-slide"
                        style="background-image: url('{{ asset('portal/images/hero/hero-4.png') }}');">
                        <div class="hero-overlay"></div>
                        <div class="hero-content">
                            <h1 class="hero-title">Pengiriman Bibit & Tanaman<br>Seluruh Indonesia </h1>
                            <p class="hero-subtitle">Bibitnesia mengundang Anda menjadi mitra kurir pengantar bibit dan
                                tanaman.</p>

                            @guest
                                <!-- Jika belum login -->
                                <a href="{{ route('login') }}" class="btn-marketplace">
                                    <i class="bi bi-box"></i> Daftar Sekarang
                                </a>
                            @endguest

                            @auth
                                <!-- Jika sudah login -->
                                <a href="{{ route('daftar.kurir') }}" class="btn-marketplace">
                                    <i class="bi bi-box"></i> Daftar Kurir
                                </a>
                            @endauth

                        </div>
                    </div>
                </div>


                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel"
                    data-bs-slide="next">
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
                {{-- Loop untuk data dari database (jika tersedia) --}}
                @if (isset($produkPopuler) && $produkPopuler->count() > 0)
                    @foreach ($produkPopuler as $produk)
                        <div class="col-lg-3 col-md-6">
                            <div class="product-card">
                                <img src="{{ asset('storage/' . $produk->foto_produk) }}"
                                    alt="{{ $produk->nama_produk }}" class="product-image">
                                <div class="product-body">
                                    <span
                                        class="product-category">{{ $produk->kategori->nama_kategori ?? 'Lainnya' }}</span>
                                    <h3 class="product-title">{{ $produk->nama_produk }}</h3>
                                    <p class="product-seller">
                                        <i class="bi bi-shop"></i> {{ $produk->user->name }}
                                    </p>
                                    <div class="product-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                    </div>
                                    <p class="product-location">
                                        <i class="bi bi-geo-alt-fill"></i> {{ $produk->lokasi ?? 'Indonesia' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Dummy Products jika tidak ada data dari database --}}
                    @php
                        $dummyProducts = [
                            [
                                'name' => 'Monstera Deliciosa Variegata',
                                'category' => 'Tanaman Hias',
                                'price' => 'Rp 850.000',
                                'seller' => 'Green Paradise',
                                'location' => 'Jakarta Selatan',
                                'image' => asset('portal/images/produk/produk-1.jpg'),
                            ],
                            [
                                'name' => 'Bibit Durian Musang King',
                                'category' => 'Tanaman Buah',
                                'price' => 'Rp 275.000',
                                'seller' => 'Tani Sejahtera',
                                'location' => 'Bogor',
                                'image' => asset('portal/images/produk/produk-2.jpg'),
                            ],
                            [
                                'name' => 'Paket Bibit Cabai Rawit',
                                'category' => 'Tanaman Sayur',
                                'price' => 'Rp 45.000',
                                'seller' => 'Urban Farming',
                                'location' => 'Bandung',
                                'image' => asset('portal/images/produk/produk-3.jpg'),
                            ],
                            [
                                'name' => 'Aglonema Red Sumatra',
                                'category' => 'Tanaman Hias',
                                'price' => 'Rp 125.000',
                                'seller' => 'Flora Indonesia',
                                'location' => 'Surabaya',
                                'image' => asset('portal/images/produk/produk-4.jpg'),
                            ],
                            [
                                'name' => 'Bibit Mangga Kiojay Unggul',
                                'category' => 'Tanaman Buah',
                                'price' => 'Rp 150.000',
                                'seller' => 'Kebun Buah Nusantara',
                                'location' => 'Semarang',
                                'image' => asset('portal/images/produk/produk-5.jpg'),
                            ],
                            [
                                'name' => 'Lidah Buaya Organik',
                                'category' => 'Tanaman Herbal',
                                'price' => 'Rp 35.000',
                                'seller' => 'Herbal Garden',
                                'location' => 'Yogyakarta',
                                'image' => asset('portal/images/produk/produk-6.jpg'),
                            ],
                            [
                                'name' => 'Philodendron Pink Princess',
                                'category' => 'Tanaman Hias',
                                'price' => 'Rp 650.000',
                                'seller' => 'Tropical Plants',
                                'location' => 'Tangerang',
                                'image' => asset('portal/images/produk/produk-7.jpg'),
                            ],
                            [
                                'name' => 'Bibit Tomat Cherry',
                                'category' => 'Tanaman Sayur',
                                'price' => 'Rp 25.000',
                                'seller' => 'Sayur Fresh',
                                'location' => 'Bekasi',
                                'image' => asset('portal/images/produk/produk-8.jpg'),
                            ],
                        ];
                    @endphp

                    @foreach ($dummyProducts as $product)
                        <div class="col-lg-3 col-md-6">
                            <div class="product-card">
                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}"
                                    class="product-image">
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
                @endif
            </div>

            <!-- CTA Banner -->
            <div class="cta-banner">
                <div class="container">
                    <h2>Temukan Ribuan Tanaman Lainnya</h2>
                    <p>Jelajahi koleksi lengkap tanaman hias, buah, sayur, dan herbal dari seluruh Indonesia</p>
                    <a href="{{ route('marketplace.index') }}" class="btn-explore">
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
                        Bibitnesia
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

    <!-- Custom JS -->
    <script src="{{ asset('portal/js/portal.js') }}"></script>
</body>

</html>
