<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.kurir.partials.head')
</head>

<body class="page-transition">
    <div id="app">
        @include('layouts.kurir.partials.sidebar')
        
        <div id="main">
            @include('layouts.kurir.partials.navbar')

            <div class="page-heading">
                <h3>@yield('page-title', '')</h3>
            </div>
            
            <div class="page-content" id="ajax-content">
                @yield('content')
            </div>

            @include('layouts.kurir.partials.footer')
        </div>
    </div>
    
    @include('layouts.kurir.partials.scripts')
    {{-- ⬇ logout di sini --}}
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Apakah Anda yakin ingin logout?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Ya, Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @yield('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.body.classList.add("loaded");
        });
    </script>
</body>

</html>
