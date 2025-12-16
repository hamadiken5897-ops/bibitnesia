<div class="products-grid">

    @forelse ($produk as $item)
        <a href="{{ route('marketplace.produk.show', $item->id_produk) }}" class="product-card-link">

            <div class="product-card">

                {{-- IMAGE --}}
                <div class="product-image-container">
                    <img src="{{ asset('storage/' . $item->foto_produk1) }}" alt="{{ $item->nama_produk }}"
                        onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">

                    <span class="product-badge {{ $item->stok > 0 ? 'badge-success' : 'badge-danger' }}">
                        {{ $item->stok > 0 ? 'TERSEDIA' : 'HABIS' }}
                    </span>
                </div>

                {{-- INFO --}}
                <div class="product-info">
                    <h3 class="product-title">{{ $item->nama_produk }}</h3>

                    <div class="product-price">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </div>

                    <div class="product-seller">
                        <i class="fas fa-store"></i>
                        {{ $item->user->name ?? 'Penjual' }}
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="product-footer">
                    <div class="product-stock">
                        <i class="fas fa-box"></i> Stok: {{ $item->stok }}
                    </div>

                    <div class="product-location">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $item->user->alamat ?? '-' }}
                    </div>
                </div>

            </div>
        </a>
    @empty
        <div class="text-center w-100 py-5 text-muted">
            <i class="fas fa-box-open fa-2x mb-3"></i>
            <p>Produk belum tersedia</p>
        </div>
    @endforelse

</div>