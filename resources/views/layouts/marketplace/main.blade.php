<!DOCTYPE html>
<html lang="id">

<head>
    @include('layouts.marketplace.partials.head')
</head>

<body>

    {{-- SIDEBAR --}}
    @include('layouts.marketplace.partials.sidebar')

    <div class="main-content">

        {{-- HEADER --}}
        @include('layouts.marketplace.partials.header')

        {{-- PAGE CONTENT --}}
        @yield('content')

    </div>

    {{-- SCRIPTS --}}
    @include('layouts.marketplace.partials.scripts')

</body>
</html>
