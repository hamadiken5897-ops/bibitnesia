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

        <li>
            <a href="{{ route('profile.own') }}">
                <i class="fas fa-user"></i>
                <span>Account</span>
            </a>
        </li>

        <li class="{{ request()->is('keranjang') ? 'active' : '' }}">
            <a href="{{ route('keranjang.index') }}">
                <i class="fas fa-shopping-cart"></i> Keranjang
            </a>
        </li>

        <li class="{{ request()->is('favorit') ? 'active' : '' }}">
            <a href="{{ route('favorit.index') }}">
                <i class="fas fa-heart"></i> Favorit
            </a>
        </li>

        <li class="{{ request()->is('pesanan') ? 'active' : '' }}">
            <a href="{{ route('pesanan.index') }}">
                <i class="fas fa-box"></i> Pesanan Saya
            </a>
        </li>

        <li class="{{ request()->is('riwayat') ? 'active' : '' }}">
            <a href="{{ route('riwayat') }}">
                <i class="fas fa-history"></i> Riwayat
            </a>
        </li>

        @auth
            {{-- USER BIASA --}}
            @if (auth()->user()->role === 'user')
                <li>
                    <a href="{{ route('penjual.register') }}">
                        <i class="fas fa-store"></i> Jadi Penjual
                    </a>
                </li>

                {{-- PENJUAL --}}
            @elseif (auth()->user()->role === 'penjual')
                <li class="{{ request()->routeIs('penjual.*') ? 'active' : '' }}">
                    <a href="{{ route('penjual.dashboard') }}">
                        <i class="fas fa-store"></i> Dashboard 
                    </a>
                </li>

                {{-- KURIR --}}
            @elseif (auth()->user()->role === 'kurir')
                <li class="{{ request()->routeIs('kurir.*') ? 'active' : '' }}">
                    <a href="{{ route('kurir.dashboard') }}">
                        <i class="fas fa-truck"></i> Dashboard
                    </a>
                </li>
            @endif
        @endauth


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
