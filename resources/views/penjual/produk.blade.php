@extends('layouts.penjual.penjual')

@section('page-title', 'Produk Saya')

@section('content')
    <div class="container-fluid">

        <div class="row g-4">

            {{-- ➕ Card Tambah Produk --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <a href="{{ route('penjual.produk.create') }}" class="add-product-card">
                    <div class="add-product-inner">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Produk</span>
                    </div>
                </a>
            </div>

            {{-- 🔁 Card Produk --}}
            @foreach ($produks as $produk)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="product-card">

                        {{-- Carousel --}}
                        <div id="carouselProduk{{ $loop->index }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">

                                <div class="carousel-item active">
                                    <img src="{{ asset('storage/' . $produk->foto_produk1) }}" class="d-block w-100">
                                </div>

                                @if ($produk->foto_produk2)
                                    <div class="carousel-item">
                                        <img src="{{ asset('storage/' . $produk->foto_produk2) }}" class="d-block w-100">
                                    </div>
                                @endif

                                @if ($produk->foto_produk3)
                                    <div class="carousel-item">
                                        <img src="{{ asset('storage/' . $produk->foto_produk3) }}" class="d-block w-100">
                                    </div>
                                @endif

                            </div>

                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselProduk{{ $loop->index }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>

                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselProduk{{ $loop->index }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>

                        {{-- Info --}}
                        <div class="product-body">
                            <h5 class="product-title">{{ $produk->nama_produk }}</h5>

                            <div class="product-meta">
                                <span class="price">Rp {{ number_format($produk->harga) }}</span>
                                <span class="stock">Stok: {{ $produk->stok }}</span>
                            </div>

                            <div class="product-actions">
                                <a href="{{ route('penjual.produk.edit', $produk->id_produk) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>
                                <button class="btn btn-sm btn-outline-danger">
                                    Hapus
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach

        </div>

    </div>
@endsection
