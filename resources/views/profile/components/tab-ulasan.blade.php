{{-- Tab Content: Ulasan (Review produk yang dibeli) --}}

<style>
    .tab-ulasan-item-box {
        transition: all 0.3s ease;
        border: 1px solid transparent;
        padding: 15px;
        border-radius: 12px;
        background: #fff;
    }
    .tab-ulasan-item-box:hover {
        border-color: #f0f0f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        transform: translateY(-2px);
    }
    .tab-ulasan-empty {
        background: linear-gradient(to right bottom, #f8f9fa, #ffffff);
        border: 1px dashed #dee2e6;
        padding: 3rem;
        border-radius: 16px;
    }
</style>

<div class="tab-ulasan-container">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0" style="color: #2c3e50;">
            <i class="bi bi-star-fill text-warning me-2"></i>Ulasan Produk
        </h5>
    </div>

    @if($user->ulasans->count() > 0)
    <div class="ulasan-list mt-2">
        @foreach($user->ulasans as $ulasan)
        <div class="tab-ulasan-item-box mb-3 border-bottom pb-3">
            <div class="d-flex gap-3">
                {{-- Gambar Produk --}}
                <div class="ulasan-produk-image">
                    @if($ulasan->produk && $ulasan->produk->foto_produk1)
                        <img src="{{ asset('storage/' . $ulasan->produk->foto_produk1) }}" alt="{{ $ulasan->produk->nama_produk }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                    @else
                        <img src="https://via.placeholder.com/80x80" alt="Produk" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                    @endif
                </div>

                {{-- Detail Ulasan --}}
                <div class="flex-grow-1">
                    <a href="{{ route('marketplace.show', $ulasan->id_produk) }}" class="text-decoration-none" style="color: #34495e;">
                        <h6 class="fw-bold mb-1">{{ $ulasan->produk->nama_produk ?? 'Produk Dihapus' }}</h6>
                    </a>
                    <div class="ulasan-rating mb-2" style="font-size: 0.9rem;">
                        <span style="color: #F59E0B; text-shadow: 0 1px 2px rgba(245, 158, 11, 0.3);">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $ulasan->rating ? 'bi bi-star-fill' : 'bi bi-star' }}"></i>
                            @endfor
                        </span>
                        <span class="ms-2 fw-semibold text-muted">{{ $ulasan->rating }}/5</span>
                    </div>
                    <p class="ulasan-text mb-2 text-dark" style="line-height: 1.6; font-size: 0.95rem;">
                        {{ $ulasan->komentar }}
                    </p>
                    <small class="text-muted" style="font-size: 0.8rem;">
                        <i class="far fa-clock me-1"></i>{{ $ulasan->created_at->format('d M Y') }}
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    {{-- Belum ada ulasan --}}
    <div class="text-center tab-ulasan-empty mt-3">
        <i class="bi bi-chat-heart-fill mb-3" style="font-size: 3.5rem; color: #dcdde1; display: inline-block;"></i>
        <h5 class="fw-bold" style="color: #7f8c8d;">Belum Ada Ulasan</h5>
        @if($isOwner ?? false)
        <p class="text-muted mb-4" style="font-size: 0.95rem;">Anda belum memberikan ulasan pada produk apapun. Mari belanja dan beri ulasan!</p>
        <a href="{{ route('marketplace.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm" style="font-weight: 500;">
            <i class="bi bi-shop me-1"></i>Belanja Sekarang
        </a>
        @else
        <p class="text-muted" style="font-size: 0.95rem;">Pengguna ini belum memberikan ulasan.</p>
        @endif
    </div>
    @endif

</div>