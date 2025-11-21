<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{ route('admin.dashboard') }}">
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
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                    <a href="{{ route('profile.show') }}" class="sidebar-link">
                        <i class="iconly-boldProfile"></i>
                        <span>Profile</span>
                    </a>
                </li>

                <li class="sidebar-title">Management</li>

                <li class="sidebar-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <a href="{{ route('admin.users') }}" class='sidebar-link'>
                        <i class="bi bi-hexagon-fill"></i>
                        <span>Users Management</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('admin.produk') ? 'active' : '' }}">
                    <a href="{{ route('admin.produk') }}" class='sidebar-link'>
                        <i class="bi bi-file-earmark-medical-fill"></i>
                        <span>Product Management</span>
                    </a>
                </li>

                <li class="sidebar-item has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-pen-fill"></i>
                        <span>Payment Management</span>
                    </a>
                </li>

                <li class="sidebar-title">Services</li>

                <li class="sidebar-item has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-pen-fill"></i>
                        <span>Komplain</span>
                    </a>
                </li>

                <li class="sidebar-item has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-pen-fill"></i>
                        <span>Antrian Validasi</span>
                    </a>
                </li>

                <li class="sidebar-item has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-pen-fill"></i>
                        <span>Aktivitas</span>
                    </a>
                </li>

                <li class="sidebar-title">Teams</li>

                <li class="sidebar-item has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-pen-fill"></i>
                        <span>Staff</span>
                    </a>
                </li>

                <li class="sidebar-item has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-pen-fill"></i>
                        <span>Team Conversation</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="sidebar-link"
                            style="background:none; border:none; width:100%; text-align:left; padding:0;">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Log out</span>
                        </button>
                    </form>
                </li>



        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
                    const btn = document.getElementById("user-menu-btn");
                    const dropdown = document.getElementById("user-dropdown");
                    btn.addEventListener("click", (e) => {
                        e.stopPropagation();
                        dropdown.classList.toggle("opacity-0");
                        dropdown.classList.toggle("scale-95");
                        dropdown.classList.toggle("pointer-events-none");
                    });
                    document.addEventListener("click", () => {
                            dropdown.classList.add("opacity-0", "scale-95", "pointer-
                                events - none ");
                            });
                    });
    </script>
</div>
