{{-- Tab Content: Ulasan (Review produk yang dibeli) --}}

<div class="tab-ulasan-container">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-star me-2"></i>Ulasan Produk
        </h5>
    </div>

    {{-- 
    TODO: Implementasi sistem ulasan
    Untuk sementara tampilkan placeholder
    --}}

    @php
        // Nanti ambil ulasan dari database
        // Contoh query (sesuaikan dengan struktur database Anda):
        // $ulasans = \App\Models\Ulasan::where('id_user', $user->id_user)->get();
        $ulasans = collect(); // Empty collection untuk sementara
    @endphp

    @if($ulasans->count() > 0)
    <div class="ulasan-list">
        @foreach($ulasans as $ulasan)
        <div class="ulasan-item">
            <div class="d-flex gap-3">
                {{-- Gambar Produk --}}
                <div class="ulasan-produk-image">
                    <img src="https://via.placeholder.com/80x80" alt="Produk">
                </div>

                {{-- Detail Ulasan --}}
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1">Nama Produk</h6>
                    <div class="ulasan-rating mb-2">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star text-warning"></i>
                        <span class="ms-2 text-muted">4/5</span>
                    </div>
                    <p class="ulasan-text mb-2">
                        Produk bagus, sesuai deskripsi. Pengiriman cepat dan packing rapi.
                    </p>
                    <small class="text-muted">
                        <i class="bi bi-calendar3 me-1"></i>17 Agustus 2025
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    {{-- Belum ada ulasan --}}
    <div class="empty-state">
        <i class="bi bi-star"></i>
        <h5>Belum Ada Ulasan</h5>
        @if($isOwner ?? false)
        <p class="text-muted">Anda belum memberikan ulasan pada produk apapun.</p>
        <a href="{{ route('marketplace.index') }}" class="btn btn-primary">
            <i class="bi bi-shop me-1"></i>Belanja Sekarang
        </a>
        @else
        <p class="text-muted">User ini belum memberikan ulasan.</p>
        @endif
    </div>
    @endif

</div>

{{-- 
CATATAN UNTUK DEVELOPMENT SELANJUTNYA:
==================================================
Fitur Ulasan yang akan ditambahkan:
1. User dapat memberikan rating & review setelah pembelian
2. Upload foto produk yang dibeli (optional)
3. Seller dapat merespon ulasan
4. Filter ulasan (terbaru, rating tertinggi, rating terendah)
5. Helpful review (like/unlike)

Database yang diperlukan:
- Table: ulasans (id, id_user, id_produk, id_transaksi, rating, review, created_at)
- Table: ulasan_images (id, id_ulasan, image_path)
- Table: ulasan_responses (id, id_ulasan, id_penjual, response, created_at)

Relasi:
- User hasMany Ulasan
- Produk hasMany Ulasan
- Ulasan belongsTo User
- Ulasan belongsTo Produk
- Ulasan hasOne Response (from seller)
==================================================
--}}