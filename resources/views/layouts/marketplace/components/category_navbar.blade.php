<div class="category-navbar">
    <h3 class="category-title">Kategori Produk</h3>

    <div class="category-grid">

        {{-- Tanaman Hias --}}
        <div class="category-card cat-hias"
            onclick="window.location.href='{{ route('marketplace.index', ['kategori' => 'Tanaman_hias']) }}'">
            <img src="https://images.unsplash.com/photo-1466781783364-36c955e42a7f?w=500" alt="Tanaman Hias">
            <div class="category-overlay">
                <div class="category-icon">🌺</div>
                <div class="category-name">Tanaman Hias</div>
            </div>
        </div>

        {{-- Buah-buahan --}}
        <div class="category-card cat-buah"
            onclick="window.location.href='{{ route('marketplace.index', ['kategori' => 'buah']) }}'">
            <img src="https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=500" alt="Buah">
            <div class="category-overlay">
                <div class="category-icon">🍎</div>
                <div class="category-name">Buah-buahan</div>
            </div>
        </div>

        {{-- Sayuran --}}
        <div class="category-card cat-sayur"
            onclick="window.location.href='{{ route('marketplace.index', ['kategori' => 'sayur']) }}'">
            <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?w=500" alt="Sayuran">
            <div class="category-overlay">
                <div class="category-icon">🥬</div>
                <div class="category-name">Sayuran</div>
            </div>
        </div>

        {{-- Lainnya --}}
        <div class="category-card cat-lainnya" onclick="window.location.href='{{ route('marketplace.index') }}'">
            <img src="https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?w=500" alt="Lainnya">
            <div class="category-overlay">
                <div class="category-icon">🌿</div>
                <div class="category-name">Lainnya</div>
            </div>
        </div>

    </div>
</div>
