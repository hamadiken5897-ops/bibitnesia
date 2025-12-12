<div class="search-filter-section">
    <form action="{{ route('marketplace.index') }}" method="GET">
        <div class="search-bar">
            <input type="text" name="search" placeholder="Cari tanaman, bibit, atau lainnya..."
                   value="{{ request('search') }}">
            <button type="submit"><i class="fas fa-search"></i> Cari</button>
        </div>

        <div class="filter-row">

            <div class="filter-item">
                <label>Harga Minimum</label>
                <input type="number" name="harga_min" value="{{ request('harga_min') }}">
            </div>

            <div class="filter-item">
                <label>Harga Maximum</label>
                <input type="number" name="harga_max" value="{{ request('harga_max') }}">
            </div>

            <div class="filter-item">
                <label>Lokasi</label>
                <select name="lokasi">
                    <option value="">Semua Lokasi</option>
                    <option value="jakarta">Jakarta</option>
                    <option value="bandung">Bandung</option>
                    <option value="surabaya">Surabaya</option>
                </select>
            </div>

            <div class="filter-item">
                <label>Urutkan</label>
                <select name="sort" onchange="this.form.submit()">
                    <option value="">Terbaru</option>
                    <option value="termurah">Termurah</option>
                    <option value="termahal">Termahal</option>
                </select>
            </div>

        </div>
    </form>
</div>
