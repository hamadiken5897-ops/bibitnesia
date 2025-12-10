<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produk->nama_produk }} - Bibitnesia</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: white;
            border: 2px solid #27ae60;
            color: #27ae60;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #27ae60;
            color: white;
        }

        .product-detail-wrapper {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .product-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 30px;
        }

        .product-images {
            position: sticky;
            top: 20px;
        }

        .main-carousel {
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .main-carousel img {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

        .thumbnail-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .thumbnail {
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .thumbnail:hover,
        .thumbnail.active {
            border-color: #27ae60;
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info h1 {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .product-price {
            font-size: 36px;
            font-weight: 700;
            color: #27ae60;
            margin-bottom: 20px;
        }

        .product-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #7f8c8d;
        }

        .meta-item i {
            color: #27ae60;
        }

        .seller-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #e8f5e9;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .seller-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #27ae60;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: 700;
        }

        .seller-details h3 {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .seller-details p {
            color: #7f8c8d;
            margin: 0;
            font-size: 14px;
        }

        .product-description {
            margin-bottom: 25px;
        }

        .product-description h3 {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .product-description p {
            color: #7f8c8d;
            line-height: 1.8;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
        }

        .btn-add-cart,
        .btn-buy-now {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-add-cart {
            background: white;
            border: 2px solid #27ae60;
            color: #27ae60;
        }

        .btn-add-cart:hover {
            background: #e8f5e9;
        }

        .btn-buy-now {
            background: #27ae60;
            color: white;
        }

        .btn-buy-now:hover {
            background: #229954;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        .related-products {
            margin-top: 50px;
        }

        .related-products h2 {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .related-card {
            background: white;
            border: 2px solid #ecf0f1;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: #27ae60;
        }

        .related-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .related-info {
            padding: 15px;
        }

        .related-title {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .related-price {
            font-size: 18px;
            font-weight: 700;
            color: #27ae60;
        }

        @media (max-width: 768px) {
            .product-detail-grid {
                grid-template-columns: 1fr;
            }

            .related-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="{{ route('marketplace.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Kembali ke Marketplace
        </a>

        <div class="product-detail-wrapper">
            <div class="product-detail-grid">
                <!-- Product Images -->
                <div class="product-images">
                    <div id="mainCarousel" class="carousel slide main-carousel" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @if($produk->gambar_1)
                                <div class="carousel-item active">
                                    <img src="{{ asset('storage/' . $produk->gambar_1) }}" alt="{{ $produk->nama_produk }}">
                                </div>
                            @endif
                            @if($produk->gambar_2)
                                <div class="carousel-item {{ !$produk->gambar_1 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $produk->gambar_2) }}" alt="{{ $produk->nama_produk }}">
                                </div>
                            @endif
                            @if($produk->gambar_3)
                                <div class="carousel-item {{ !$produk->gambar_1 && !$produk->gambar_2 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $produk->gambar_3) }}" alt="{{ $produk->nama_produk }}">
                                </div>
                            @endif
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>

                    <div class="thumbnail-grid">
                        @if($produk->gambar_1)
                            <div class="thumbnail active" data-bs-target="#mainCarousel" data-bs-slide-to="0">
                                <img src="{{ asset('storage/' . $produk->gambar_1) }}" alt="Thumbnail 1">
                            </div>
                        @endif
                        @if($produk->gambar_2)
                            <div class="thumbnail" data-bs-target="#mainCarousel" data-bs-slide-to="1">
                                <img src="{{ asset('storage/' . $produk->gambar_2) }}" alt="Thumbnail 2">
                            </div>
                        @endif
                        @if($produk->gambar_3)
                            <div class="thumbnail" data-bs-target="#mainCarousel" data-bs-slide-to="2">
                                <img src="{{ asset('storage/' . $produk->gambar_3) }}" alt="Thumbnail 3">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Info -->
                <div class="product-info">
                    <h1>{{ $produk->nama_produk }}</h1>
                    <div class="product-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>

                    <div class="product-meta">
                        <div class="meta-item">
                            <i class="fas fa-box"></i>
                            <span>Stok: {{ $produk->stok }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-tag"></i>
                            <span>{{ ucfirst(str_replace('_', ' ', $produk->kategori)) }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-star"></i>
                            <span>4.8 (120 ulasan)</span>
                        </div>
                    </div>

                    <div class="seller-info">
                        <div class="seller-avatar">
                            {{ substr($produk->nama_penjual, 0, 1) }}
                        </div>
                        <div class="seller-details">
                            <h3>{{ $produk->nama_penjual }}</h3>
                            <p><i class="fas fa-map-marker-alt"></i> {{ $produk->lokasi_penjual }} | <i
                                    class="fas fa-phone"></i> {{ $produk->kontak_penjual }}</p>
                        </div>
                    </div>

                    <div class="product-description">
                        <h3>Deskripsi Produk</h3>
                        <p>{{ $produk->deskripsi }}</p>
                    </div>

                    <div class="action-buttons">
                        <button class="btn-add-cart">
                            <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                        </button>
                        <button class="btn-buy-now">
                            <i class="fas fa-bolt"></i> Beli Sekarang
                        </button>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if($produkTerkait->count() > 0)
                <div class="related-products">
                    <h2>Produk Terkait</h2>
                    <div class="related-grid">
                        @foreach($produkTerkait as $item)
                            <div class="related-card"
                                onclick="window.location.href='{{ route('marketplace.show', $item->id_produk) }}'">
                                <img src="{{ $item->gambar_1 ? asset('storage/' . $item->gambar_1) : 'https://via.placeholder.com/300x200' }}"
                                    alt="{{ $item->nama_produk }}">
                                <div class="related-info">
                                    <div class="related-title">{{ $item->nama_produk }}</div>
                                    <div class="related-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Thumbnail click handler
        document.querySelectorAll('.thumbnail').forEach((thumb, index) => {
            thumb.addEventListener('click', function () {
                document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>

    <form action="{{ route('keranjang.add') }}" method="POST">
        @csrf
        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
        <button class="btn btn-success">Tambah ke Keranjang</button>
    </form>
</body>

</html>