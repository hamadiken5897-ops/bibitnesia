@extends('layouts.marketplace.main')

@section('content')
    <div class="container">
        <div class="product-detail-wrapper">
            <div class="product-detail-grid">

                <!-- Product Images -->
                <div class="product-images">
                    <div id="mainCarousel" class="carousel slide main-carousel" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @if ($produk->foto_produk1)
                                <div class="carousel-item active">
                                    <img src="{{ asset('storage/' . $produk->foto_produk1) }}">
                                </div>
                            @endif
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
                    </div>

                    <div class="thumbnail-grid">
                        @if ($produk->foto_produk1)
                            <div class="thumbnail active" data-bs-target="#mainCarousel" data-bs-slide-to="0">
                                <img src="{{ asset('storage/' . $produk->foto_produk1) }}">
                            </div>
                        @endif
                        @if ($produk->foto_produk2)
                            <div class="thumbnail" data-bs-target="#mainCarousel" data-bs-slide-to="1">
                                <img src="{{ asset('storage/' . $produk->foto_produk2) }}">
                            </div>
                        @endif
                        @if ($produk->foto_produk3)
                            <div class="thumbnail" data-bs-target="#mainCarousel" data-bs-slide-to="2">
                                <img src="{{ asset('storage/' . $produk->foto_produk3) }}">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Info -->
                <div class="product-info">
                    <h1>{{ $produk->nama_produk }}</h1>
                    <div class="product-price">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </div>

                    <div class="product-meta">
                        <div class="meta-item">
                            <i class="fas fa-box"></i> Stok: {{ $produk->stok }}
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-tag"></i>
                            {{ ucfirst(str_replace('_', ' ', $produk->kategori)) }}
                        </div>
                    </div>

                    <div class="seller-info">
                        <div class="seller-avatar">
                            @if (!empty($produk->penjual->user->profile_image))
                                <img src="{{ asset('storage/' . $produk->penjual->user->profile_image) }}"
                                    alt="{{ $produk->penjual->nama_penjual }}">
                            @else
                                {{ strtoupper(substr($produk->penjual->nama_penjual ?? 'P', 0, 1)) }}
                            @endif
                        </div>

                        <div class="seller-details">
                            <h3>{{ $produk->penjual->nama_penjual ?? 'Penjual' }}</h3>

                            <p>
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $produk->penjual->provinsi->nama_provinsi ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="product-section">
                        <h5 class="section-title">Deskripsi Produk</h5>
                        <p class="section-text">{{ $produk->deskripsi }}</p>
                    </div>


                    {{-- JUMLAH & TOTAL --}}
                    <div class="product-purchase-box">

                        <div class="qty-wrapper">
                            <label for="qty">Jumlah</label>
                            <div class="qty-control">
                                <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                                <input type="number" id="qty" value="1" min="1"
                                    max="{{ $produk->stok }}">
                                <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                            </div>
                        </div>

                        <div class="total-wrapper">
                            <span>Total Harga</span>
                            <strong id="totalHarga">
                                Rp {{ number_format($produk->harga, 0, ',', '.') }}
                            </strong>
                        </div>

                    </div>


                    <div class="action-buttons">
                        <form id="checkoutForm" action="{{ route('checkout.create') }}" method="POST"
                            style="display:none;">
                            @csrf
                            <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                            <input type="hidden" name="jumlah" id="checkoutJumlah">
                        </form>

                        <button class="btn-add-cart">
                            <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                        </button>

                        <button class="btn-buy-now" onclick="confirmCheckout()">
                            <i class="fas fa-bolt"></i> Beli Sekarang
                        </button>
                    </div>


                </div>

            </div>
        </div>
    </div>
    <script>
        const hargaSatuan = {{ $produk->harga }};
        const stokMaks = {{ $produk->stok }};
        const qtyInput = document.getElementById('qty');
        const totalHarga = document.getElementById('totalHarga');

        function updateTotal() {
            let qty = parseInt(qtyInput.value);

            if (qty < 1) qty = 1;
            if (qty > stokMaks) qty = stokMaks;

            qtyInput.value = qty;
            totalHarga.innerText = 'Rp ' + (hargaSatuan * qty).toLocaleString('id-ID');
        }

        function changeQty(amount) {
            qtyInput.value = parseInt(qtyInput.value) + amount;
            updateTotal();
        }

        qtyInput.addEventListener('input', updateTotal);

        function confirmCheckout() {
            const qty = qtyInput.value;

            Swal.fire({
                title: 'Lanjutkan Pembayaran?',
                html: `
            <p><strong>{{ $produk->nama_produk }}</strong></p>
            <p>Jumlah: <strong>${qty}</strong></p>
            <p>Total: <strong>Rp ${(hargaSatuan * qty).toLocaleString('id-ID')}</strong></p>
        `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#27ae60',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, Lanjutkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('checkoutJumlah').value = qty;
                    document.getElementById('checkoutForm').submit();
                }
            });
        }

        function confirmCheckout() {
            const qty = document.getElementById('qty').value;

            window.location.href = `/checkout?id_produk={{ $produk->id_produk }}&jumlah=${qty}`;
        }
    </script>
@endsection
