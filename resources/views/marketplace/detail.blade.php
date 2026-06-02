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
                    
                    {{-- Bintang Rating --}}
                    <div class="d-flex align-items-center mb-3">
                        <div class="text-warning me-2">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($rataRataRating))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-muted small">({{ $jumlahUlasan }} Ulasan)</span>
                    </div>

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
                            <a href="{{ route('profile.show', $produk->penjual->id_user) }}" class="text-decoration-none text-dark d-flex align-items-center justify-content-center h-100 w-100">
                                @if (!empty($produk->penjual->user->profile_image))
                                    <img src="{{ asset('storage/' . $produk->penjual->user->profile_image) }}"
                                        alt="{{ $produk->penjual->nama_penjual }}">
                                @else
                                    {{ strtoupper(substr($produk->penjual->nama_penjual ?? 'P', 0, 1)) }}
                                @endif
                            </a>
                        </div>

                        <div class="seller-details d-flex justify-content-between align-items-center w-100">
                            <div>
                                <a href="{{ route('profile.show', $produk->penjual->id_user) }}" class="text-decoration-none text-dark">
                                    <h3>{{ $produk->penjual->nama_penjual ?? 'Penjual' }}</h3>
                                </a>
                                <p>
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $produk->penjual->provinsi->nama_provinsi ?? '-' }}
                                </p>
                            </div>
                            
                            {{-- Tombol Tanya Penjual --}}
                            @auth
                                <form action="{{ route('pesan.tanya', $produk->id_produk) }}" method="GET" class="mb-0">
                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                        <i class="fas fa-comment-dots"></i> Tanya Penjual
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                    <i class="fas fa-comment-dots"></i> Tanya Penjual
                               </a>
                            @endauth
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
                        <form id="checkoutForm" action="{{ route('checkout.create') }}" method="GET"
                            style="display:none;">
                            <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                            <input type="hidden" name="jumlah" id="checkoutJumlah">
                        </form>

                        @auth
                            <button class="btn btn-outline-danger btn-add-favorit" onclick="toggleFavorit()" title="Tambahkan ke Favorit">
                                <i id="favoritIcon" class="{{ $isFavorit ? 'fas' : 'far' }} fa-heart"></i>
                            </button>

                            <button class="btn-add-cart" onclick="addToCart()">
                                <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                            </button>

                            <button class="btn-buy-now" onclick="confirmCheckout()">
                                <i class="fas fa-bolt"></i> Beli Sekarang
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-danger btn-add-favorit text-center" style="text-decoration:none; padding: 12px;" title="Tambahkan ke Favorit">
                                <i class="fas fa-heart"></i>
                            </a>

                            <a href="{{ route('login') }}" class="btn-add-cart text-center" style="text-decoration:none;">
                                <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                            </a>

                            <a href="{{ route('login') }}" class="btn-buy-now text-center" style="text-decoration:none;">
                                <i class="fas fa-bolt"></i> Beli Sekarang
                            </a>
                        @endauth
                    </div>


                </div>

            </div>
        </div>

        {{-- ULASAN SECTION --}}
        <div class="product-section mt-5 pt-4 border-top">
            <h4 class="section-title mb-4 fw-bold" style="color: #2c3e50;"><i class="fas fa-star text-warning me-2"></i> Ulasan Produk</h4>
            
            <style>
                .ulasan-form-card {
                    background: linear-gradient(145deg, #ffffff, #f8f9fa);
                    border: 1px solid rgba(0,0,0,0.05);
                    box-shadow: 0 8px 20px rgba(0,0,0,0.04);
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }
                .ulasan-form-card:hover {
                    box-shadow: 0 12px 25px rgba(0,0,0,0.06);
                }
                .ulasan-item-box {
                    transition: all 0.3s ease;
                    border: 1px solid transparent;
                    padding: 15px;
                    border-radius: 12px;
                }
                .ulasan-item-box:hover {
                    background: #fff;
                    border-color: #f0f0f0;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
                    transform: translateY(-2px);
                }
                .star-rating-display i {
                    color: #F59E0B;
                    text-shadow: 0 1px 2px rgba(245, 158, 11, 0.3);
                }
                .empty-ulasan-box {
                    background: linear-gradient(to right bottom, #f8f9fa, #ffffff);
                    border: 1px dashed #dee2e6;
                }
            </style>
            
            @if ($bisaUlas)
                <div class="card ulasan-form-card border-0 mb-5 p-4 rounded-4">
                    <h6 class="fw-bold mb-3" style="color: #27ae60;"><i class="fas fa-pen-alt me-2"></i> Beri Ulasan Anda</h6>
                    <form action="{{ route('ulasan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="produk_id" value="{{ $produk->id_produk }}">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted">Rating Bintang</label>
                            <select name="rating" class="form-select w-auto shadow-sm" required style="border-radius: 8px;">
                                <option value="5">⭐⭐⭐⭐⭐ (5/5 Sangat Bagus)</option>
                                <option value="4">⭐⭐⭐⭐ (4/5 Bagus)</option>
                                <option value="3">⭐⭐⭐ (3/5 Cukup)</option>
                                <option value="2">⭐⭐ (2/5 Kurang)</option>
                                <option value="1">⭐ (1/5 Sangat Kurang)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <textarea name="komentar" class="form-control shadow-sm" rows="3" placeholder="Ceritakan pengalaman Anda menggunakan produk ini..." style="border-radius: 12px; resize: none;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success rounded-pill px-5 shadow-sm" style="background-color: #27ae60; border:none; font-weight: 500;">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Ulasan
                        </button>
                    </form>
                </div>
            @endif

            <div class="ulasan-list mt-2">
                @forelse ($produk->ulasans as $ulasan)
                    <div class="d-flex mb-3 ulasan-item-box">
                        <div class="me-3">
                            @if ($ulasan->user->profile_image)
                                <img src="{{ asset('storage/' . $ulasan->user->profile_image) }}" class="rounded-circle shadow-sm" width="55" height="55" style="object-fit:cover; border: 2px solid #fff;">
                            @else
                                <div class="rounded-circle bg-success bg-gradient text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 55px; height: 55px; font-size: 1.5rem; font-weight: 600; border: 2px solid #fff;">
                                    {{ strtoupper(substr($ulasan->user->nama ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="fs-6" style="color: #34495e;">{{ $ulasan->user->nama }}</strong>
                                <small class="text-muted" style="font-size: 0.8rem;"><i class="far fa-clock me-1"></i> {{ $ulasan->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="star-rating-display mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $ulasan->rating ? 'fas' : 'far' }} fa-star" style="font-size: 0.9rem;"></i>
                                @endfor
                            </div>
                            <p class="mb-0 text-dark" style="line-height: 1.6; font-size: 0.95rem;">{{ $ulasan->komentar }}</p>
                        </div>
                    </div>
                    <hr class="text-muted my-2 mx-3" style="opacity: 0.1;">
                @empty
                    <div class="text-center py-5 empty-ulasan-box rounded-4 mt-3">
                        <i class="bi bi-chat-heart-fill mb-3" style="font-size: 3rem; color: #dcdde1; display: inline-block;"></i>
                        <h5 class="fw-bold" style="color: #7f8c8d;">Belum Ada Ulasan</h5>
                        <p class="mb-0 text-muted" style="font-size: 0.95rem;">Jadilah yang pertama memberikan ulasan setelah membeli produk ini!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <script>
        var hargaSatuan = {{ $produk->harga }};
        var stokMaks = {{ $produk->stok }};

        function updateTotal() {
            var qtyInput = document.getElementById('qty');
            var totalHarga = document.getElementById('totalHarga');
            
            if(!qtyInput || !totalHarga) return;

            let qty = parseInt(qtyInput.value);

            if (qty < 1) qty = 1;
            if (qty > stokMaks) qty = stokMaks;

            qtyInput.value = qty;
            totalHarga.innerText = 'Rp ' + (hargaSatuan * qty).toLocaleString('id-ID');
        }

        function changeQty(amount) {
            var qtyInput = document.getElementById('qty');
            if(!qtyInput) return;
            qtyInput.value = parseInt(qtyInput.value) + amount;
            updateTotal();
        }

        document.addEventListener('input', function(e) {
            if(e.target && e.target.id === 'qty') {
                updateTotal();
            }
        });

        function addToCart() {
            const qty = document.getElementById('qty').value;
            const produk_id = '{{ $produk->id_produk }}';
            
            fetch("{{ route('keranjang.add') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    produk_id: produk_id,
                    qty: qty
                })
            }).then(response => response.json())
              .then(data => {
                  if (data.status === 'success') {
                      Swal.fire({
                          icon: 'success',
                          title: 'Berhasil',
                          text: data.message,
                          timer: 1500,
                          showConfirmButton: false
                      });
                  } else {
                      Swal.fire({icon: 'error', title: 'Oops', text: 'Terjadi kesalahan'});
                  }
              }).catch(err => {
                  Swal.fire({icon: 'error', title: 'Oops', text: 'Gagal menghubungi server'});
              });
        }

        function toggleFavorit() {
            const produk_id = '{{ $produk->id_produk }}';
            fetch("{{ route('favorit.toggle') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    produk_id: produk_id
                })
            }).then(response => response.json())
              .then(data => {
                  const icon = document.getElementById('favoritIcon');
                  if (data.status === 'added') {
                      icon.classList.remove('far');
                      icon.classList.add('fas');
                      Swal.fire({icon: 'success', title: 'Berhasil', text: data.message, timer: 1500, showConfirmButton: false});
                  } else if (data.status === 'removed') {
                      icon.classList.remove('fas');
                      icon.classList.add('far');
                      Swal.fire({icon: 'info', title: 'Berhasil', text: data.message, timer: 1500, showConfirmButton: false});
                  }
              }).catch(err => {
                  Swal.fire({icon: 'error', title: 'Oops', text: 'Gagal menghubungi server'});
              });
        }

        function confirmCheckout() {
            const qtyInput = document.getElementById('qty');
            if(!qtyInput) return;
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
    </script>
@endsection
