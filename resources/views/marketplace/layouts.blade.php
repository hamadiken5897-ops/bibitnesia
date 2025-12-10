<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibitnesia Marketplace - Belanja Tanaman & Bibit</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
                <a href="{{ route('marketplace.index') }}">
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
                <a href="{{ route('pesanan.index') }}">
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

    <!-- Main -->
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

        {{-- ➤ HALAMAN LAIN MASUK DI SINI --}}
        @yield('content')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('marketplace_as/js/marketplace.js') }}"></script>

</body>

</html>
