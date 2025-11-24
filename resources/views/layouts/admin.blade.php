<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.partials.head')
</head>

<body class="page-transition">
    <div id="app">
        @include('layouts.partials.sidebar')
        
        <div id="main">
            @include('layouts.partials.navbar')

            <div class="page-heading">
                <h3>@yield('page-title', '')</h3>
            </div>
            
            <div class="page-content">
                @yield('content')
            </div>

            @include('layouts.partials.footer')
        </div>
    </div>
    
    @include('layouts.partials.scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.body.classList.add("loaded");
        });
    </script>
</body>

</html>