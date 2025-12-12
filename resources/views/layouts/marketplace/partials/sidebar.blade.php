<div class="sidebar">
    <div class="sidebar-logo">
        <i class="fas fa-seedling"></i>
        <span>Bibitnesia</span>
    </div>

    <ul class="sidebar-menu">
        <li class="{{ request()->is('marketplace') ? 'active' : '' }}">
            <a href="{{ route('marketplace.index') }}">
                <i class="fas fa-home"></i> Beranda
            </a>
        </li>

        <li class="{{ request()->is('keranjang') ? 'active' : '' }}">
            <a href="{{ route('keranjang.index') }}">
                <i class="fas fa-shopping-cart"></i> Keranjang
            </a>
        </li>

        <li class="{{ request()->is('favorit') ? 'active' : '' }}">
            <a href="#">
                <i class="fas fa-heart"></i> Favorit
            </a>
        </li>

        <li class="{{ request()->is('pesanan') ? 'active' : '' }}">
            <a href="{{ route('pesanan.index') }}">
                <i class="fas fa-box"></i> Pesanan Saya
            </a>
        </li>

        <li>
            <a href="#">
                <i class="fas fa-history"></i> Riwayat
            </a>
        </li>

        <li>
            <a href="#">
                <i class="fas fa-store"></i> Jadi Penjual
            </a>
        </li>

        <li>
            <a href="#">
                <i class="fas fa-cog"></i> Pengaturan
            </a>
        </li>

        <li>
            <a href="#">
                <i class="fas fa-question-circle"></i> Bantuan
            </a>
        </li>
    </ul>
</div>
