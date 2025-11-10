<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.partials.head')
</head>

<body>
    <div id="app">
        @include('layouts.partials.sidebar')

        <div id="main" class="layout-navbar">
            @include('layouts.partials.navbar')

            <div id="main-content">
                {{-- Konten dinamis di sini --}}
                @yield('content')

                @include('layouts.partials.footer')
            </div>
        </div>
    </div>

    <script src="{{ asset('dist/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('dist/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/assets/js/main.js') }}"></script>
</body>
</html>
