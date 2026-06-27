@extends('layouts.penjual.penjual')

@section('page-title', 'Produk Saya')

@section('content')

    {{-- Alert Success --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ===== EMPTY STATE ===== --}}
    @if ($produks->isEmpty())

        <div class="text-center py-5">
            <img src="https://img.icons8.com/?size=100&id=8rvKNd0gtsym&format=png&color=000000" class="img-fluid mb-3"
                style="max-width:200px;">

            <h4 class="fw-bold">Belum ada produk</h4>
            <p class="text-muted">
                Tambahkan produk pertamamu agar bisa tampil di marketplace Bibitnesia.
            </p>

            <a href="{{ route('penjual.produk.create') }}" class="btn btn-success mt-2">
                + Tambah Produk
            </a>
        </div>
    @else
        <div class="row g-4">

            {{-- CARD TAMBAH PRODUK --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <a href="{{ route('penjual.produk.create') }}" class="add-product-card">
                    <div class="add-product-inner">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Produk</span>
                    </div>
                </a>
            </div>

            {{-- LIST PRODUK --}}
            @foreach ($produks as $produk)
                <div class="col-xl-3 col-lg-4 col-md-6">

                    <div class="product-card" style="{{ in_array($produk->status, ['hidden', 'dihapus_admin']) ? 'opacity: 0.6; border: 1px solid red;' : '' }}">

                        @if ($produk->foto_produk2 || $produk->foto_produk3)
                            <div id="carousel{{ $produk->id_produk }}" class="carousel slide product-carousel"
                                data-bs-ride="carousel" data-bs-touch="true">

                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="{{ asset('storage/' . $produk->foto_produk1) }}">
                                    </div>

                                    @if ($produk->foto_produk2)
                                        <div class="carousel-item">
                                            <img src="{{ asset('storage/' . $produk->foto_produk2) }}">
                                        </div>
                                    @endif

                                    @if ($produk->foto_produk3)
                                        <div class="carousel-item">
                                            <img src="{{ asset('storage/' . $produk->foto_produk3) }}">
                                        </div>
                                    @endif
                                </div>

                                {{-- CONTROL (ID HARUS SAMA) --}}
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carousel{{ $produk->id_produk }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>

                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carousel{{ $produk->id_produk }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            </div>
                        @else
                            <div class="product-image-single">
                                <img src="{{ asset('storage/' . $produk->foto_produk1) }}">
                            </div>
                        @endif

                        {{-- INFO PRODUK --}}
                        <div class="product-body">
                            <h6 class="fw-bold mb-1">{{ $produk->nama_produk }}</h6>

                            <div class="d-flex justify-content-between small text-muted">
                                <span>Rp {{ number_format($produk->harga) }}</span>
                                <span>Stok: {{ $produk->stok }}</span>
                            </div>

                            @if($produk->status === 'hidden')
                                <div class="mt-2"><span class="badge bg-warning text-dark w-100">Disembunyikan Admin</span></div>
                            @elseif($produk->status === 'dihapus_admin')
                                <div class="mt-2"><span class="badge bg-danger w-100">Dihapus Admin</span></div>
                            @endif

                            <div class="mt-2 d-flex gap-2">
                                <a href="{{ route('penjual.produk.edit', $produk->id_produk) }}"
                                    class="btn btn-sm btn-outline-primary w-100">
                                    Detail / Edit
                                </a>

                                @if($produk->status !== 'dihapus_admin')
                                <form action="{{ route('penjual.produk.destroy', $produk->id_produk) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach

        </div>

    @endif
@endsection
