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

                        <button type="button" class="btn btn-primary w-100 mt-4" onclick="nextStep()">
                            Lanjutkan <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                    {{-- STEP 2 --}}
                    <div class="step hidden" id="step2">

                        <h5 class="section-title">Alamat Pengiriman</h5>

                        @if(count($alamats) > 0)
                            <div class="mb-3">
                                <label class="fw-bold mb-2">Pilih Alamat Pengiriman</label>
                                @foreach($alamats as $al)
                                    <div class="card mb-2 {{ $alamatUtama && $alamatUtama->id == $al->id ? 'border-success bg-light' : '' }}">
                                        <div class="card-body py-2 px-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="alamat_id" id="alamat_{{ $al->id }}" value="{{ $al->id }}" {{ $alamatUtama && $alamatUtama->id == $al->id ? 'checked' : '' }} onchange="selectAlamat('{{ $al->provinsi->id_provinsi }}', '{{ addslashes($al->detail_alamat . ', ' . $al->kota . ', ' . $al->provinsi->nama_provinsi . ' ' . $al->kode_pos) }}')">
                                                <label class="form-check-label w-100" for="alamat_{{ $al->id }}">
                                                    <strong>{{ $al->nama_penerima }}</strong> ({{ $al->no_telepon }})
                                                    @if($al->is_utama) <span class="badge bg-success ms-1">Utama</span> @endif
                                                    <div class="small text-muted mt-1">{{ $al->detail_alamat }}, {{ $al->kecamatan ? $al->kecamatan.', ' : '' }}{{ $al->kota }}, {{ $al->provinsi->nama_provinsi }}, {{ $al->kode_pos }}</div>
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
                            <div class="alert alert-warning mb-3">
                                Anda belum memiliki alamat tersimpan. <a href="{{ route('account.alamat') }}" class="alert-link text-decoration-underline">Tambah alamat di Pengaturan</a>.
                            </div>
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
                                <textarea name="alamat" id="alamat_manual" required></textarea>
                            </div>
                        @endif

                        <button type="button" class="btn btn-primary w-100 mt-3" onclick="validateStep2()">
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
                            <p><strong>Ongkir</strong></p>
                            <p>
                                Rp {{ number_format($ongkirTetap, 0, ',', '.') }}
                                <br>
                                <small>
                                    Total Bayar:
                                    <strong>
                                        Rp {{ number_format($totalProduk + $ongkirTetap, 0, ',', '.') }}
                                    </strong>
                                </small>
                            </p>
                        </div>


                        <div class="input-group">
                            <label>Metode Pembayaran *</label>
                            <select name="metode" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="VA BANK">VA BANK BCA</option>
                                <option value="E-Wallet">E-Wallet</option>
                                <option value="QRIS">QRIS</option>
                            </select>
                        </div>

                        {{-- hidden untuk backend --}}
                        <input type="hidden" name="ongkir" value="{{ $ongkirTetap }}">

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
