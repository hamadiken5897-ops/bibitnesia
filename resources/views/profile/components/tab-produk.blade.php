{{-- Tab Content: Produk (khusus penjual) --}}

<div class="tab-produk-container">
    
    {{-- Header dengan tombol tambah (jika owner) --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-box-seam me-2"></i>Produk Dijual
        </h5>
        
        @if($isOwner)
        <a href="{{ route('produk.create') }}" class="btn btn-success btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Tambah Produk
        </a>
        @endif
    </div>

    {{-- Daftar Produk --}}
    @php
        // Ambil produk milik penjual ini
        $produks = \App\Models\Produk::where('id_penjual', $user->penjual->id_penjual ?? null)
                                     ->with('file')
                                     ->latest()
                                     ->get();
    @endphp

    @if($produks->count() > 0)
    <div class="row g-3">
        @foreach($produks as $produk)
        <div class="col-md-4 col-lg-3">
            <div class="produk-card">
                {{-- Gambar Produk --}}
                <div class="produk-image">
                    @if($produk->file)
                        <img src="{{ Storage::url($produk->file->path) }}" 
                             alt="{{ $produk->nama_produk }}">
                    @else
                        <img src="https://via.placeholder.com/300x300/27ae60/ffffff?text=No+Image" 
                             alt="{{ $produk->nama_produk }}">
                    @endif
                    
                    {{-- Badge Stok --}}
                    @if($produk->stok > 0)
                        <span class="badge-stok badge-stok-tersedia">Tersedia</span>
                    @else
                        <span class="badge-stok badge-stok-habis">Habis</span>
                    @endif
                </div>

                {{-- Info Produk --}}
                <div class="produk-body">
                    <h6 class="produk-name">{{ $produk->nama_produk }}</h6>
                    <p class="produk-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                    <p class="produk-stok">Stok: {{ $produk->stok }}</p>
                    
                    <a href="{{ route('produk.detail', $produk->id_produk) }}" 
                       class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-eye me-1"></i>Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination jika perlu --}}
    <div class="mt-4">
        <p class="text-muted text-center">Menampilkan {{ $produks->count() }} produk</p>
    </div>

    @else
    {{-- Tidak ada produk --}}
    <div class="empty-state">
        <i class="bi bi-box-seam"></i>
        <h5>Belum Ada Produk</h5>
        @if($isOwner)
        <p class="text-muted">Mulai jual produk Anda sekarang!</p>
        <a href="{{ route('produk.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i>Tambah Produk Pertama
        </a>
        @else
        <p class="text-muted">Penjual ini belum menambahkan produk.</p>
        @endif
    </div>
    @endif

</div>