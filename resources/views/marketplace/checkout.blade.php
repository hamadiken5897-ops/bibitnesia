@extends('layouts.marketplace.main')

@section('title', 'Checkout Pesanan')

@section('content')
<style>
    .option-card {
        cursor: pointer;
        display: block;
        margin-bottom: 0;
    }
    .option-card input[type="radio"] {
        display: none;
    }
    .option-card .card-content {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px;
        text-align: center;
        transition: all 0.2s ease;
        background: #fff;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .option-card .card-content img {
        max-width: 90%;
        max-height: 30px;
        object-fit: contain;
        transition: all 0.2s ease;
    }
    .option-card input[type="radio"]:checked ~ .card-content {
        border-color: #198754; /* Bootstrap success color */
        background-color: #f0fdf4;
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.2);
    }
    .option-card input[type="radio"]:not(:checked) ~ .card-content img {
        filter: grayscale(100%) opacity(0.6);
    }
</style>
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
                        @if ($is_from_cart)
                            <input type="hidden" name="is_from_cart" value="1">
                        @endif

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

                        <button type="button" class="btn btn-success rounded-pill py-2 fw-bold w-100 mt-4" onclick="nextStep()">
                            Lanjutkan <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                    {{-- STEP 2 --}}
                    <div class="step hidden" id="step2">

                        <h5 class="section-title">Alamat Pengiriman</h5>

                        @if(count($alamats) > 0)
                            <div class="mb-4">
                                <label class="fw-bold mb-3 text-muted">Pilih Alamat Pengiriman</label>
                                @foreach($alamats as $al)
                                    <div class="card mb-3 rounded-4 transition-all {{ $alamatUtama && $alamatUtama->id == $al->id ? 'border-success' : 'border-light shadow-sm' }}" style="{{ $alamatUtama && $alamatUtama->id == $al->id ? 'background-color: #f0fdf4; border-width: 2px;' : '' }}">
                                        <div class="card-body p-3">
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-3" type="radio" name="alamat_id" id="alamat_{{ $al->id }}" value="{{ $al->id }}" {{ $alamatUtama && $alamatUtama->id == $al->id ? 'checked' : '' }} onchange="selectAlamat('{{ $al->provinsi->id_provinsi }}', '{{ addslashes($al->detail_alamat . ', ' . $al->kota . ', ' . $al->provinsi->nama_provinsi . ' ' . $al->kode_pos) }}')" style="transform: scale(1.3); cursor: pointer;">
                                                <label class="form-check-label w-100" for="alamat_{{ $al->id }}" style="cursor: pointer;">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <strong class="fs-6">{{ $al->nama_penerima }}</strong> 
                                                        <span class="text-muted ms-2 small">({{ $al->no_telepon }})</span>
                                                        @if($al->is_utama) <span class="badge bg-success ms-auto rounded-pill px-3 py-2">Utama</span> @endif
                                                    </div>
                                                    <div class="small text-muted" style="line-height: 1.5;">{{ $al->detail_alamat }}, {{ $al->kecamatan ? $al->kecamatan.', ' : '' }}{{ $al->kota }}, {{ $al->provinsi->nama_provinsi }}, {{ $al->kode_pos }}</div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Hidden inputs to keep existing logic working -->
                            <input type="hidden" name="provinsi" id="hidden_provinsi" value="{{ $alamatUtama ? $alamatUtama->provinsi->id_provinsi : '' }}">
                            <input type="hidden" name="alamat" id="hidden_alamat" value="{{ $alamatUtama ? $alamatUtama->detail_alamat . ', ' . $alamatUtama->kota . ', ' . $alamatUtama->provinsi->nama_provinsi . ' ' . $alamatUtama->kode_pos : '' }}">
                        @else
                            <div class="alert alert-warning mb-4 rounded-4 border-0 shadow-sm" style="background-color: #fff8e1; color: #d35400;">
                                <i class="fas fa-exclamation-triangle me-2 text-warning"></i> Anda belum memiliki alamat tersimpan. <a href="{{ route('account.alamat') }}" class="alert-link text-decoration-underline text-warning">Tambah alamat di Pengaturan</a>.
                            </div>
                            <div class="input-group mb-3">
                                <label class="fw-bold mb-2">Provinsi *</label>
                                <select name="provinsi" id="provinsi" class="form-select rounded-pill px-4 py-2" required>
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach ($provinsi as $p)
                                        <option value="{{ $p->id_provinsi }}" data-ongkir="{{ $p->estimasi_ongkir }}">
                                            {{ $p->nama_provinsi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="input-group mb-4">
                                <label class="fw-bold mb-2">Alamat Lengkap *</label>
                                <textarea name="alamat" id="alamat_manual" class="form-control rounded-4 p-3" rows="3" required></textarea>
                            </div>
                        @endif

                        <div class="d-flex flex-column gap-2 mt-4">
                            <button type="button" class="btn btn-success rounded-pill py-2 fw-bold w-100" onclick="validateStep2()">
                                Lanjutkan <i class="fas fa-arrow-right ms-1"></i>
                            </button>

                            <button type="button" class="btn btn-outline-secondary rounded-pill py-2 w-100" onclick="prevStep()">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </button>
                        </div>

                    </div>

                    {{-- STEP 3 --}}
                    <div class="step hidden" id="step3">

                        <h5 class="section-title mb-4">Pengiriman & Pembayaran</h5>

                        <div class="card border-0 shadow-sm rounded-4 mb-4" style="background-color: #f8f9fa;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Ongkos Kirim</span>
                                    <span class="fw-bold">Rp {{ number_format($ongkirTetap, 0, ',', '.') }}</span>
                                </div>
                                <hr class="text-muted border-secondary border-dashed my-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold fs-5 text-dark">Total Bayar</span>
                                    <span class="fw-bold fs-4 text-success">Rp {{ number_format($totalProduk + $ongkirTetap, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold mb-3 d-block">Metode Pembayaran *</label>
                            
                            <div class="mb-3">
                                <small class="text-muted fw-bold d-block mb-2">Transfer Bank (Virtual Account)</small>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <label class="option-card">
                                            <input type="radio" name="metode" value="bca_va" required>
                                            <div class="card-content">
                                                <img src="{{ asset('img/banks/bca.png') }}" alt="BCA">
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="option-card">
                                            <input type="radio" name="metode" value="mandiri_va">
                                            <div class="card-content">
                                                <img src="{{ asset('img/banks/mandiri.png') }}" alt="Mandiri">
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="option-card">
                                            <input type="radio" name="metode" value="bni_va">
                                            <div class="card-content">
                                                <img src="{{ asset('img/banks/bni.png') }}" alt="BNI">
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="option-card">
                                            <input type="radio" name="metode" value="bri_va">
                                            <div class="card-content">
                                                <img src="{{ asset('img/banks/bri.png') }}" alt="BRI">
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="option-card">
                                            <input type="radio" name="metode" value="permata_va">
                                            <div class="card-content">
                                                <span class="fw-bold text-secondary">Permata</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted fw-bold d-block mb-2">E-Wallet & QRIS</small>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <label class="option-card">
                                            <input type="radio" name="metode" value="gopay">
                                            <div class="card-content">
                                                <img src="{{ asset('img/banks/gopay.png') }}" alt="GoPay">
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="option-card">
                                            <input type="radio" name="metode" value="shopeepay">
                                            <div class="card-content">
                                                <span class="fw-bold text-secondary" style="font-size:0.8rem">ShopeePay</span>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="option-card">
                                            <input type="radio" name="metode" value="other_qris">
                                            <div class="card-content">
                                                <span class="fw-bold text-secondary">QRIS</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- hidden untuk backend --}}
                        <input type="hidden" name="ongkir" id="ongkirValue" value="{{ $ongkirTetap }}">

                        <div class="d-flex flex-column gap-2 mt-4">
                            <button class="btn btn-success rounded-pill py-2 fw-bold w-100 shadow-sm">
                                <i class="fas fa-check-circle me-1"></i> Konfirmasi Pesanan
                            </button>

                            <button type="button" class="btn btn-outline-secondary rounded-pill py-2 w-100" onclick="prevStep()">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </button>
                        </div>

                    </div>


                </form>

            </div>
            <script>
                const ongkirTetap = {{ $ongkirTetap ?? 15000 }};
                let currentStep = 1;
                const totalSteps = 3;

                function showStep(step) {
                    for (let i = 1; i <= totalSteps; i++) {
                        const stepEl = document.getElementById('step' + i);
                        const circleEl = document.getElementById('circle' + i);

                        if (i === step) {
                            stepEl.classList.remove('hidden');
                            circleEl.classList.add('active');
                            circleEl.innerHTML = i;
                        } else if (i < step) {
                            stepEl.classList.add('hidden');
                            circleEl.classList.add('active');
                            circleEl.innerHTML = '<i class="fas fa-check"></i>';
                        } else {
                            stepEl.classList.add('hidden');
                            circleEl.classList.remove('active');
                            circleEl.innerHTML = i;
                        }
                    }

                    const progress = ((step - 1) / (totalSteps - 1)) * 100;
                    document.getElementById('progressLine').style.width = progress + '%';

                    // 🔥 INI PENTING
                    if (step === 3) {
                        document.getElementById('ongkirValue').value = ongkirTetap;
                    }
                }

                function hitungOngkir() {
                    const provinsiSelect = document.getElementById('provinsi');
                    const selected = provinsiSelect.options[provinsiSelect.selectedIndex];

                    if (!selected || !selected.dataset.ongkir) {
                        document.getElementById('ongkirText').innerText = 'Pilih provinsi terlebih dahulu';
                        return;
                    }

                    const ongkir = parseInt(selected.dataset.ongkir);
                    document.getElementById('ongkirValue').value = ongkir;

                    document.getElementById('ongkirText').innerHTML = `Rp ${ongkir.toLocaleString('id-ID')}<br>
        <small>Total Bayar:
            <strong>
                Rp ${(totalProduk + ongkir).toLocaleString('id-ID')}
            </strong>
        </small>
    `;
                }

                function selectAlamat(idProvinsi, alamatLengkap) {
                    document.getElementById('hidden_provinsi').value = idProvinsi;
                    document.getElementById('hidden_alamat').value = alamatLengkap;
                }

                function validateStep2() {
                    const alamatsExists = {{ count($alamats) > 0 ? 'true' : 'false' }};
                    
                    if (alamatsExists) {
                        const prov = document.getElementById('hidden_provinsi').value;
                        const alam = document.getElementById('hidden_alamat').value;
                        if (!prov || !alam) {
                            alert("Silakan pilih alamat terlebih dahulu.");
                            return;
                        }
                    } else {
                        const prov = document.getElementById('provinsi').value;
                        const alam = document.getElementById('alamat_manual').value;
                        if (!prov || !alam.trim()) {
                            alert("Silakan lengkapi Provinsi dan Alamat.");
                            return;
                        }
                    }
                    nextStep();
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
