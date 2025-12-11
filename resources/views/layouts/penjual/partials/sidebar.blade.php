<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{ route('penjual.dashboard') }}">
                        <img src="{{ asset('dist/assets/images/logo/logo bibitnesia.png') }}" alt="Logo"
                            style="width:175px; height:auto;">
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu Penjual</li>

                {{-- Dashboard --}}
                <li class="sidebar-item {{ request()->routeIs('penjual.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('penjual.dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Produk Saya --}}
                <li class="sidebar-item {{ request()->routeIs('penjual.produk') ? 'active' : '' }}">
                    <a href="{{ route('penjual.produk') }}" class="sidebar-link">
                        <i class="bi bi-box-seam"></i>
                        <span>Produk Saya</span>
                    </a>
                </li>

                {{-- Tambah Produk --}}
                <li class="sidebar-item {{ request()->routeIs('penjual.produk.tambah') ? 'active' : '' }}">
                    <a href="{{ route('penjual.produk.tambah') }}" class='sidebar-link'>
                        <i class="bi bi-plus-circle"></i>
                        <span>Tambah Produk</span>
                    </a>
                </li>

                {{-- Pesanan Masuk --}}
                <li class="sidebar-item {{ request()->routeIs('penjual.pesanan') ? 'active' : '' }}">
                    <a href="{{ route('penjual.pesanan') }}" class='sidebar-link'>
                        <i class="bi bi-bag-check-fill"></i>
                        <span>Pesanan Masuk</span>
                    </a>
                </li>

                {{-- Pembayaran / Keuangan --}}
                <li class="sidebar-item {{ request()->routeIs('penjual.pembayaran') ? 'active' : '' }}">
                    <a href="{{ route('penjual.pembayaran') }}" class='sidebar-link'>
                        <i class="bi bi-credit-card-fill"></i>
                        <span>Pembayaran</span>
                    </a>
                </li>

                {{-- Pengaturan Toko --}}
                <li class="sidebar-item {{ request()->routeIs('penjual.pengaturan') ? 'active' : '' }}">
                    <a href="{{ route('penjual.pengaturan') }}" class='sidebar-link'>
                        <i class="bi bi-gear-fill"></i>
                        <span>Pengaturan Toko</span>
                    </a>
                </li>

                {{-- Logout --}}
                <li class="sidebar-item">
                    <button type="button" class="sidebar-link"
                        style="background:none; border:none; width:100%; text-align:left; padding:0;"
                        data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Log out</span>
                    </button>
                </li>

            </ul>
        </div>

        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const btn = document.getElementById("user-menu-btn");
            const dropdown = document.getElementById("user-dropdown");

            if(btn){
                btn.addEventListener("click", (e) => {
                    e.stopPropagation();
                    dropdown.classList.toggle("opacity-0");
                    dropdown.classList.toggle("scale-95");
                    dropdown.classList.toggle("pointer-events-none");
                });
                document.addEventListener("click", () => {
                    dropdown.classList.add("opacity-0", "scale-95", "pointer-events-none");
                });
            }
        });
    </script>
</div>
