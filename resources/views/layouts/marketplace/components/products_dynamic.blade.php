{{-- PRODUK DINAMIS DARI DATABASE --}}
@if (isset($produk) && $produk->count() > 0)

    @foreach ($produk as $index => $item)
        <div class="product-card">

            <div class="product-image-container">

                {{-- CAROUSEL GAMBAR --}}
                <div id="carouselDB{{ $index }}" class="carousel slide product-carousel" data-bs-ride="carousel">
                    <div class="carousel-inner">

                        {{-- GAMBAR 1 --}}
                        @if ($item->gambar_1)
                            <div class="carousel-item active">
                                <img src="{{ asset('storage/' . $item->gambar_1) }}" alt="{{ $item->nama_produk }}">
                            </div>
                        @endif

                        {{-- GAMBAR 2 --}}
                        @if ($item->gambar_2)
                            <div class="carousel-item {{ !$item->gambar_1 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $item->gambar_2) }}" alt="{{ $item->nama_produk }}">
                            </div>
                        @endif

                        {{-- GAMBAR 3 --}}
                        @if ($item->gambar_3)
                            <div class="carousel-item {{ !$item->gambar_1 && !$item->gambar_2 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $item->gambar_3) }}" alt="{{ $item->nama_produk }}">
                            </div>
                        @endif

                        {{-- JIKA TIDAK ADA GAMBAR --}}
                        @if (!$item->gambar_1 && !$item->gambar_2 && !$item->gambar_3)
                            <div class="carousel-item active">
                                <img src="https://via.placeholder.com/400x300?text=No+Image" alt="No Image">
                            </div>
                        @endif

                    </div>

                    {{-- BUTTON NEXT / PREV JIKA LEBIH DARI 1 GAMBAR --}}
                    @if ($item->gambar_1 && ($item->gambar_2 || $item->gambar_3))
                        <button class="carousel-control-prev" type="button"
                            data-bs-target="#carouselDB{{ $index }}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>

                        <button class="carousel-control-next" type="button"
                            data-bs-target="#carouselDB{{ $index }}" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    @endif

                </div>

                {{-- BADGE STATUS PRODUK --}}
                @if ($item->status === 'aktif')
                    <span class="product-badge">TERSEDIA</span>
                @else
                    <span class="product-badge bg-danger">HABIS</span>
                @endif

            </div>


            <div class="product-info">
                <h3 class="product-title">{{ $item->nama_produk }}</h3>
                <div class="product-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>

                <div class="product-seller">
                    <i class="fas fa-store"></i> {{ $item->nama_penjual }}
                </div>
            </div>


            <div class="product-footer">
                <div class="product-stock">
                    <i class="fas fa-box"></i> Stok: {{ $item->stok }}
                </div>

                <div class="product-location">
                    <i class="fas fa-map-marker-alt"></i> {{ $item->lokasi_penjual }}
                </div>
            </div>

        </div>
    @endforeach

@endif
