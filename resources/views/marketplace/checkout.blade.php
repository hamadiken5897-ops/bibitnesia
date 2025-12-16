@extends('layouts.marketplace.main')

@section('title', 'Checkout Pesanan')

@section('content')
    <div class="checkout-page">
        <div class="container my-5">

            <div class="checkout-card">


                <h3 class="checkout-title">
                    <i class="fas fa-credit-card"></i> Checkout Pesanan
                </h3>
                <p class="text-muted mb-4">
                    Lengkapi data berikut untuk menyelesaikan pesanan Anda.
                </p>

                {{-- VALIDATION --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Periksa kembali input Anda:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- PROGRESS --}}
                <div class="progress-container">
                    <div class="progress-line"></div>
                    <div class="progress-line-fill" id="progressLine"></div>

                    <div class="step-circle active" id="circle1">1</div>
                    <div class="step-circle" id="circle2">2</div>
                    <div class="step-circle" id="circle3">3</div>
                </div>

                {{-- FORM --}}
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf

                    {{-- STEP 1 --}}
                    <div class="step" id="step1">

                        <h5 class="section-title">Ringkasan Pesanan</h5>

                        <div class="order-list">

                            @foreach ($items as $item)
                                <div class="order-item">

                                    <div class="order-info">
                                        <strong>{{ $item['nama'] }}</strong>
                                        <small>
                                            {{ $item['jumlah'] }} ×
                                            Rp {{ number_format($item['harga'], 0, ',', '.') }}
                                        </small>
                                    </div>

                                    <div class="order-subtotal">
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </div>

                                    {{-- hidden untuk backend --}}
                                    <input type="hidden" name="items[{{ $loop->index }}][id_produk]"
                                        value="{{ $item['id_produk'] }}">
                                    <input type="hidden" name="items[{{ $loop->index }}][jumlah]"
                                        value="{{ $item['jumlah'] }}">
                                </div>
                            @endforeach

                            <div class="order-total">
                                <span>Total Produk</span>
                                <strong>
                                    Rp {{ number_format($totalProduk, 0, ',', '.') }}
                                </strong>
                            </div>

                        </div>

                        <button type="button" class="btn btn-primary w-100 mt-4" onclick="nextStep()">
                            Lanjutkan <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                    {{-- STEP 2 --}}
                    <div class="step hidden" id="step2">

                        <h5 class="section-title">Alamat Pengiriman</h5>

                        <div class="input-group">
                            <label>Provinsi *</label>
                            <select name="provinsi" id="provinsi" required>
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach ($provinsi as $p)
                                    <option value="{{ $p->id_provinsi }}" data-ongkir="{{ $p->estimasi_ongkir }}">
                                        {{ $p->nama_provinsi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-group">
                            <label>Alamat Lengkap *</label>
                            <textarea name="alamat" required></textarea>
                        </div>

                        <button type="button" class="btn btn-primary w-100 mt-3" onclick="nextStep()">
                            Lanjutkan →
                        </button>

                        <button type="button" class="btn btn-secondary w-100 mt-3" onclick="prevStep()">
                            ← Kembali
                        </button>

                    </div>

                    {{-- STEP 3 --}}
                    <div class="step hidden" id="step3">

                        <h5 class="section-title">Pengiriman & Pembayaran</h5>

                        <div class="shipping-box">
                            <p><strong>Estimasi Ongkir</strong></p>
                            <p id="ongkirText">Pilih provinsi terlebih dahulu</p>
                        </div>

                        <div class="input-group">
                            <label>Metode Pembayaran *</label>
                            <select name="metode" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="VA BANK">VA BANK</option>
                                <option value="E-Wallet">E-Wallet</option>
                                <option value="QRIS">QRIS</option>
                            </select>
                        </div>

                        {{-- hidden untuk backend --}}
                        <input type="hidden" name="ongkir" id="ongkirValue">

                        <button class="btn btn-success w-100 mt-3">
                            Konfirmasi Pesanan
                        </button>

                        <button type="button" class="btn btn-secondary w-100 mt-3" onclick="prevStep()">
                            ← Kembali
                        </button>

                    </div>


                </form>

            </div>
            <script>
                let currentStep = 1;
                const totalSteps = 3;

                function showStep(step) {
                    for (let i = 1; i <= totalSteps; i++) {
                        const stepEl = document.getElementById('step' + i);
                        const circleEl = document.getElementById('circle' + i);

                        if (i === step) {
                            stepEl.classList.remove('hidden');
                            circleEl.classList.add('active');
                        } else {
                            stepEl.classList.add('hidden');
                            circleEl.classList.remove('active');
                        }
                    }

                    // progress bar
                    const progress = ((step - 1) / (totalSteps - 1)) * 100;
                    document.getElementById('progressLine').style.width = progress + '%';
                }

                function nextStep() {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        showStep(currentStep);
                    }
                }

                function prevStep() {
                    if (currentStep > 1) {
                        currentStep--;
                        showStep(currentStep);
                    }
                }

                // init
                document.addEventListener('DOMContentLoaded', () => {
                    showStep(currentStep);
                });
            </script>


        </div>
    @endsection
