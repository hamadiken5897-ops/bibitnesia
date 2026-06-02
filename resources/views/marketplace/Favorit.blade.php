@extends('layouts.marketplace.main')

@section('content')
<style>
    .fav-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    .page-title {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 30px;
        position: relative;
        padding-bottom: 10px;
    }
    .page-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        height: 4px;
        width: 60px;
        background: #e74c3c;
        border-radius: 2px;
    }
    .fav-card {
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .fav-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .fav-img-wrap {
        position: relative;
        height: 220px;
        overflow: hidden;
    }
    .fav-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .fav-card:hover .fav-img-wrap img {
        transform: scale(1.08);
    }
    .btn-delete-fav {
        position: absolute;
        top: 15px;
        right: 15px;
        background: white;
        border: none;
        color: #e74c3c;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        transition: all 0.3s;
        z-index: 10;
    }
    .btn-delete-fav:hover {
        background: #e74c3c;
        color: white;
        transform: scale(1.1);
    }
    .fav-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .fav-title {
        font-weight: 700;
        font-size: 1.1rem;
        color: #2c3e50;
        margin-bottom: 5px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .fav-price {
        font-size: 1.25rem;
        font-weight: 800;
        color: #27ae60;
        margin-bottom: 15px;
    }
    .btn-add-cart-full {
        background: white;
        color: #27ae60;
        border: 2px solid #27ae60;
        border-radius: 50px;
        font-weight: 600;
        padding: 10px;
        width: 100%;
        transition: all 0.3s ease;
        margin-top: auto;
    }
    .btn-add-cart-full:hover {
        background: #27ae60;
        color: white;
        box-shadow: 0 5px 15px rgba(39, 174, 96, 0.2);
    }
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .empty-state i {
        font-size: 80px;
        color: #ffb8b8;
        margin-bottom: 20px;
    }
    .empty-state h4 {
        font-weight: 700;
        color: #2c3e50;
    }
</style>

<div class="container py-5 fav-container">

    <h2 class="page-title">Favorit Saya</h2>

    @if ($favorit->count() == 0)
        <div class="empty-state border-0">
            <i class="bi bi-heart-break-fill"></i>
            <h4>Belum Ada Produk Favorit</h4>
            <p class="text-muted mb-4">Anda belum menambahkan tanaman apapun ke daftar favorit.</p>
            <a href="{{ route('marketplace.index') }}" class="btn btn-checkout px-5" style="background: linear-gradient(135deg, #e74c3c, #c0392b); border: none; border-radius: 50px; color: white; padding: 12px 30px; font-weight: 600; text-decoration: none;">
                Eksplorasi Tanaman
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach ($favorit as $item)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card bg-white shadow-sm fav-card border-0">
                        
                        <div class="fav-img-wrap">
                            {{-- Hapus Favorit Button Absolute --}}
                            <form action="{{ route('favorit.delete', $item->id) }}" method="POST" onsubmit="return confirm('Hapus dari daftar favorit?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete-fav" title="Hapus dari Favorit">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                            
                            <img src="{{ asset('storage/' . $item->produk->foto_produk1) }}" alt="{{ $item->produk->nama_produk }}">
                            
                            {{-- Optional Badge for Category --}}
                            <div style="position: absolute; bottom: 10px; left: 10px;">
                                <span class="badge bg-dark text-white opacity-75 px-2 py-1">
                                    {{ str_replace('_', ' ', ucfirst($item->produk->kategori)) }}
                                </span>
                            </div>
                        </div>

                        <div class="fav-body">
                            <a href="{{ route('marketplace.show', $item->produk->id_produk) }}" class="text-decoration-none">
                                <h3 class="fav-title">{{ $item->produk->nama_produk }}</h3>
                            </a>
                            
                            <div class="text-muted small mb-2">
                                <i class="bi bi-shop"></i> {{ $item->produk->penjual->nama_penjual ?? 'Penjual' }}
                            </div>

                            <div class="fav-price">
                                Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                            </div>

                            <form action="{{ route('keranjang.add') }}" method="POST" class="mt-auto">
                                @csrf
                                <input type="hidden" name="produk_id" value="{{ $item->produk->id_produk }}">
                                <button type="submit" class="btn-add-cart-full">
                                    <i class="bi bi-cart-plus-fill"></i> Tambah Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
