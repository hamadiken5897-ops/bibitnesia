@extends('layouts.marketplace.main')

@section('content')
<style>
    /* Custom Styling for Keranjang */
    .cart-container {
        max-width: 1100px;
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
        background: #27ae60;
        border-radius: 2px;
    }
    .cart-item-card {
        border-radius: 16px;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .cart-item-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important;
    }
    .product-img-wrap {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
    }
    .product-img-wrap img {
        transition: transform 0.5s ease;
    }
    .cart-item-card:hover .product-img-wrap img {
        transform: scale(1.05);
    }
    .qty-control {
        background: #f8f9fa;
        border-radius: 50px;
        padding: 5px;
        display: inline-flex;
        align-items: center;
        border: 1px solid #e9ecef;
    }
    .qty-control button {
        background: white;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2c3e50;
        transition: all 0.2s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .qty-control button:hover {
        background: #27ae60;
        color: white;
    }
    .qty-control input {
        border: none;
        background: transparent;
        width: 40px;
        text-align: center;
        font-weight: 600;
        color: #2c3e50;
    }
    .qty-control input:focus {
        outline: none;
    }
    /* Hide arrows on number input */
    .qty-control input::-webkit-outer-spin-button,
    .qty-control input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .btn-update {
        font-size: 12px;
        font-weight: 600;
        border-radius: 50px;
        padding: 5px 15px;
        letter-spacing: 0.5px;
    }
    .btn-delete {
        color: #e74c3c;
        background: rgba(231, 76, 60, 0.1);
        border: none;
        border-radius: 50px;
        padding: 8px 15px;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
    }
    .btn-delete:hover {
        background: #e74c3c;
        color: white;
    }
    .summary-card {
        border-radius: 16px;
        position: sticky;
        top: 100px;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .summary-title {
        font-weight: 700;
        color: #2c3e50;
        border-bottom: 2px dashed #ecf0f1;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    .summary-item {
        font-size: 15px;
        color: #7f8c8d;
        margin-bottom: 12px;
    }
    .summary-total {
        font-size: 20px;
        font-weight: 800;
        color: #27ae60;
        border-top: 2px dashed #ecf0f1;
        padding-top: 20px;
        margin-top: 10px;
    }
    .btn-checkout {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        border: none;
        border-radius: 50px;
        color: white;
        font-weight: 600;
        padding: 12px;
        font-size: 16px;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        transition: all 0.3s ease;
    }
    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
    }
    .empty-cart {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-cart i {
        font-size: 80px;
        color: #bdc3c7;
        margin-bottom: 20px;
    }
    .empty-cart h4 {
        font-weight: 700;
        color: #2c3e50;
    }
</style>

<div class="container py-5 cart-container">
    <h2 class="page-title">Keranjang Saya</h2>

    @if($keranjang->count() == 0)
        <div class="card shadow-sm border-0 empty-cart" style="border-radius: 16px;">
            <i class="bi bi-cart-x"></i>
            <h4>Keranjang Anda Kosong</h4>
            <p class="text-muted mb-4">Sepertinya Anda belum memilih tanaman apapun. Yuk, temukan tanaman favorit Anda!</p>
            <a href="{{ route('marketplace.index') }}" class="btn btn-checkout px-5" style="display:inline-block; text-decoration:none;">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="row g-4">
            {{-- Left Column: Cart Items --}}
            <div class="col-lg-8">
                @foreach($keranjang as $item)
                    <div class="card shadow-sm bg-white cart-item-card mb-4 p-3">
                        <div class="row align-items-center">
                            {{-- Image --}}
                            <div class="col-md-3 col-4">
                                <div class="product-img-wrap">
                                    <img src="{{ asset('storage/' . $item->produk->foto_produk1) }}"
                                         class="img-fluid"
                                         style="object-fit: cover; width: 100%; height: 110px; border-radius: 8px;">
                                </div>
                            </div>

                            {{-- Product Details --}}
                            <div class="col-md-5 col-8">
                                <span class="badge bg-light text-success mb-2 px-2 py-1" style="font-weight: 500;">
                                    {{ str_replace('_', ' ', ucfirst($item->produk->kategori)) ?? 'Kategori' }}
                                </span>
                                <h5 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">
                                    <a href="{{ route('marketplace.show', $item->produk->id_produk) }}" class="text-decoration-none text-dark">
                                        {{ $item->produk->nama_produk }}
                                    </a>
                                </h5>
                                <div class="text-success fw-bold" style="font-size: 1.1rem;">
                                    Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                                </div>
                                <div class="text-muted small mt-1">
                                    <i class="bi bi-shop"></i> {{ $item->produk->penjual->nama_penjual ?? 'Penjual' }}
                                </div>
                            </div>

                            {{-- Quantity & Actions --}}
                            <div class="col-md-4 col-12 mt-3 mt-md-0 d-flex flex-column align-items-md-end justify-content-between h-100">
                                
                                {{-- Update Qty --}}
                                <form action="{{ route('keranjang.update', $item->id) }}" method="POST" class="d-flex flex-column align-items-md-end mb-2">
                                    @csrf
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="qty-control me-2">
                                            <button type="button" onclick="this.parentNode.querySelector('input[name=qty]').stepDown();" title="Kurangi">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input type="number" name="qty" value="{{ $item->qty }}" min="1" max="{{ $item->produk->stok }}">
                                            <button type="button" onclick="this.parentNode.querySelector('input[name=qty]').stepUp();" title="Tambah">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                        <button type="submit" class="btn btn-outline-success btn-update" title="Simpan perubahan">
                                            Update
                                        </button>
                                    </div>
                                    <div class="text-muted small text-end">Stok: {{ $item->produk->stok }}</div>
                                </form>

                                {{-- Delete --}}
                                <form action="{{ route('keranjang.delete', $item->id) }}" method="POST" class="form-delete-cart">
                                    @csrf @method('DELETE')
                                    <button class="btn-delete">
                                        <i class="bi bi-trash3"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Right Column: Summary --}}
            <div class="col-lg-4">
                <div class="card shadow-sm bg-white summary-card p-4">
                    <h4 class="summary-title"><i class="bi bi-receipt"></i> Ringkasan Belanja</h4>
                    
                    <div class="d-flex justify-content-between summary-item">
                        <span>Total Item</span>
                        <span class="fw-bold text-dark">{{ $keranjang->sum('qty') }} item</span>
                    </div>

                    <div class="d-flex justify-content-between summary-item">
                        <span>Subtotal Produk</span>
                        <span class="fw-bold text-dark">Rp {{ number_format($keranjang->sum(fn($i) => $i->qty * $i->produk->harga), 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between summary-total">
                        <span>Total Belanja</span>
                        <span>Rp {{ number_format($keranjang->sum(fn($i) => $i->qty * $i->produk->harga), 0, ',', '.') }}</span>
                    </div>

                    <form action="{{ route('checkout.create') }}" method="GET" class="mt-4">
                        <input type="hidden" name="from_cart" value="1">
                        <button type="submit" class="btn btn-checkout w-100">
                            <i class="bi bi-shield-check"></i> Lanjutkan ke Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.form-delete-cart');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus Item?',
                    text: "Apakah Anda yakin ingin menghapus produk ini dari keranjang?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74c3c',
                    cancelButtonColor: '#95a5a6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
