@extends('layouts.marketplace.main')

@section('content')

    @include('layouts.marketplace.components.search_filter')
    @include('layouts.marketplace.components.category_navbar')

    <div class="products-section">
        @include('layouts.marketplace.components.products_static')
        @include('layouts.marketplace.components.products_dynamic')
        @include('layouts.marketplace.components.pagination')
    </div>

@endsection
