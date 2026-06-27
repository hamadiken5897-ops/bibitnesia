@extends('layouts.marketplace.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center mb-5">
        <div class="col-md-8 text-center">
            <h2 class="fw-bold text-success mb-3">Pusat Bantuan BibitNesia</h2>
            <p class="text-muted lead">Temukan jawaban atas pertanyaan Anda atau hubungi layanan pelanggan kami.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-4 hover-shadow transition-all" style="background: linear-gradient(145deg, #ffffff, #f0fdf4);">
                <div class="card-body">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success text-white mb-4" style="width: 70px; height: 70px; font-size: 30px;">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h5 class="fw-bold">Panduan Pembeli</h5>
                    <p class="text-muted small">Pelajari cara mencari, membeli, dan melacak pesanan bibit tanaman favorit Anda.</p>
                    <button class="btn btn-outline-success rounded-pill btn-sm mt-2 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#faqPembeli">Lihat FAQ</button>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-4 hover-shadow transition-all" style="background: linear-gradient(145deg, #ffffff, #f0fdf4);">
                <div class="card-body">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success text-white mb-4" style="width: 70px; height: 70px; font-size: 30px;">
                        <i class="fas fa-store"></i>
                    </div>
                    <h5 class="fw-bold">Panduan Penjual</h5>
                    <p class="text-muted small">Informasi lengkap tentang cara membuka toko, mengelola produk, dan mencairkan dana.</p>
                    <button class="btn btn-outline-success rounded-pill btn-sm mt-2 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#faqPenjual">Lihat FAQ</button>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-4 hover-shadow transition-all" style="background: linear-gradient(145deg, #ffffff, #fff8e1);">
                <div class="card-body">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle text-white mb-4" style="width: 70px; height: 70px; font-size: 30px; background-color: #f39c12;">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5 class="fw-bold">Butuh Bantuan Lain?</h5>
                    <p class="text-muted small">Tim Customer Service kami selalu siap sedia membantu menyelesaikan masalah Anda.</p>
                    <a href="{{ route('account.cs') }}" class="btn rounded-pill btn-sm mt-2 px-4 text-white" style="background-color: #f39c12;">Hubungi CS</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <!-- FAQ Pembeli -->
            <div class="collapse show mb-4" id="faqPembeli">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold text-success mb-4"><i class="fas fa-question-circle me-2"></i> FAQ Pembeli</h4>
                        <div class="accordion accordion-flush" id="accordionPembeli">
                            <div class="accordion-item border-0 mb-3 rounded-4 shadow-sm">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed rounded-4 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" style="background-color: #f8f9fa;">
                                        Bagaimana cara memesan bibit?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionPembeli">
                                    <div class="accordion-body text-muted">
                                        Cari produk yang Anda inginkan di halaman Beranda. Klik "Beli" atau tambahkan ke Keranjang. Setelah itu, masuk ke halaman Keranjang, pilih produk, dan klik "Checkout" untuk memproses pembayaran.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border-0 mb-3 rounded-4 shadow-sm">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed rounded-4 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" style="background-color: #f8f9fa;">
                                        Apa saja metode pembayaran yang tersedia?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionPembeli">
                                    <div class="accordion-body text-muted">
                                        BibitNesia mendukung berbagai metode pembayaran melalui sistem Payment Gateway (Midtrans), termasuk Transfer Bank (Virtual Account), e-Wallet (GoPay, OVO, ShopeePay), dan pembayaran via minimarket.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border-0 mb-3 rounded-4 shadow-sm">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed rounded-4 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" style="background-color: #f8f9fa;">
                                        Bagaimana cara melacak pesanan saya?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionPembeli">
                                    <div class="accordion-body text-muted">
                                        Anda dapat pergi ke halaman <strong>Pesanan Saya</strong> di menu samping, pilih pesanan yang sedang diproses, dan Anda akan melihat status terkini serta posisi kurir.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Penjual -->
            <div class="collapse mb-4" id="faqPenjual">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold text-success mb-4"><i class="fas fa-store me-2"></i> FAQ Penjual</h4>
                        <div class="accordion accordion-flush" id="accordionPenjual">
                            <div class="accordion-item border-0 mb-3 rounded-4 shadow-sm">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed rounded-4 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeller1" style="background-color: #f8f9fa;">
                                        Bagaimana cara mendaftar menjadi penjual?
                                    </button>
                                </h2>
                                <div id="collapseSeller1" class="accordion-collapse collapse" data-bs-parent="#accordionPenjual">
                                    <div class="accordion-body text-muted">
                                        Buka menu samping dan klik <strong>Jadi Penjual</strong>. Anda akan diminta untuk mengisi formulir pendaftaran mitra. Setelah disetujui oleh admin, menu Dashboard Penjual akan aktif.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border-0 mb-3 rounded-4 shadow-sm">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed rounded-4 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeller2" style="background-color: #f8f9fa;">
                                        Bagaimana cara menambahkan produk baru?
                                    </button>
                                </h2>
                                <div id="collapseSeller2" class="accordion-collapse collapse" data-bs-parent="#accordionPenjual">
                                    <div class="accordion-body text-muted">
                                        Di Dashboard Penjual, masuk ke tab <strong>Produk</strong>, lalu klik tombol "Tambah Produk". Isi detail lengkap mengenai tanaman Anda beserta foto yang menarik.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .accordion-button:not(.collapsed) {
        background-color: #e8f5e9 !important;
        color: #2e7d32;
        box-shadow: none;
    }
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }
</style>
@endsection
