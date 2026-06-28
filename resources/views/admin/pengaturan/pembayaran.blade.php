@extends('layouts.admin.admin')

@section('title', 'Pengaturan Pembayaran')

@section('content')
<style>
    .payment-card-preview {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 16px;
        color: white;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.3);
        position: relative;
        overflow: hidden;
        margin: 20px auto;
        max-width: 400px;
        height: 230px;
        transition: all 0.3s ease;
    }
    
    .payment-card-preview.theme-dark {
        background: linear-gradient(135deg, #141e30 0%, #243b55 100%);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .payment-card-preview.theme-gold {
        background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        color: #333;
        box-shadow: 0 10px 30px rgba(253, 160, 133, 0.4);
    }

    .payment-card-preview.theme-green {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        box-shadow: 0 10px 30px rgba(17, 153, 142, 0.3);
    }

    .card-chip {
        width: 45px;
        height: 35px;
        background: linear-gradient(135deg, #e5b252 0%, #ffe58f 100%);
        border-radius: 6px;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
    }
    
    .card-chip::after {
        content: '';
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 1px;
        background: rgba(0,0,0,0.2);
    }

    .card-bank-name {
        position: absolute;
        top: 25px;
        right: 25px;
        font-weight: 700;
        font-size: 1.2rem;
        font-style: italic;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .card-number {
        font-size: 1.6rem;
        letter-spacing: 3px;
        font-family: 'Courier New', Courier, monospace;
        margin-bottom: 25px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }

    .card-owner-label {
        font-size: 0.65rem;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 2px;
    }

    .card-owner-name {
        font-size: 1.1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .card-contactless {
        position: absolute;
        bottom: 25px;
        right: 25px;
        font-size: 1.5rem;
        opacity: 0.8;
    }

    .settings-section {
        border-radius: 12px;
        border: 1px solid rgba(0,0,0,0.08);
        background: #fff;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    .settings-header {
        padding: 16px 24px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .settings-header.primary {
        color: #4361ee;
        background: rgba(67, 97, 238, 0.03);
    }
    
    .settings-header.info {
        color: #00b4d8;
        background: rgba(0, 180, 216, 0.03);
    }

    .settings-header.warning {
        color: #f77f00;
        background: rgba(247, 127, 0, 0.03);
    }

    .settings-body {
        padding: 24px;
    }

    .form-group label {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        margin-bottom: 8px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
    }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Pengaturan Pembayaran</h3>
        <button type="submit" form="settings-form" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-save me-2"></i> Simpan Pengaturan
        </button>
    </div>
</div>

<div class="page-content">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.pengaturan.pembayaran.update') }}" method="POST" id="settings-form">
        @csrf

        <div class="row">
            <!-- Left Column: Bank Account -->
            <div class="col-lg-6">
                <div class="settings-section">
                    <div class="settings-header primary">
                        <i class="bi bi-bank"></i> Rekening Bank Utama (Manual Transfer)
                    </div>
                    <div class="settings-body">
                        
                        <!-- Card Preview -->
                        <div class="payment-card-preview theme-{{ $pengaturan->card_theme ?? 'blue' }}" id="cardPreview">
                            <div class="card-chip"></div>
                            <div class="card-bank-name" id="previewBankName">
                                {{ $pengaturan->bank_name ?: 'BANK / DOMPET ELEKTRONIK' }}
                            </div>
                            <div class="card-number" id="previewAccountNumber">
                                {{ $pengaturan->bank_account ? implode(' ', str_split(str_pad($pengaturan->bank_account, 16, '0'), 4)) : '0000 0000 0000 0000' }}
                            </div>
                            <div class="card-owner-label">Atas Nama</div>
                            <div class="card-owner-name" id="previewOwnerName">
                                {{ $pengaturan->bank_owner ?: 'PEMILIK' }}
                            </div>
                            <div class="card-contactless">
                                <i class="bi bi-wifi" style="transform: rotate(90deg); display: inline-block;"></i>
                            </div>
                        </div>

                        <div class="form-group mb-3 mt-4">
                            <label>Pilih Bank</label>
                            <input type="text" name="bank_name" class="form-control" placeholder="Contoh: BCA, Mandiri, BRI" value="{{ $pengaturan->bank_name }}" id="inputBankName">
                        </div>

                        <div class="form-group mb-3">
                            <label>Nomor Rekening</label>
                            <input type="text" name="bank_account" class="form-control" placeholder="0000000000" value="{{ $pengaturan->bank_account }}" id="inputAccountNumber">
                        </div>

                        <div class="form-group mb-3">
                            <label>Atas Nama (A/N)</label>
                            <input type="text" name="bank_owner" class="form-control" placeholder="Nama Pemilik Rekening" value="{{ $pengaturan->bank_owner }}" id="inputOwnerName">
                        </div>

                        <div class="form-group mb-3">
                            <label>Warna Tema Kartu</label>
                            <select name="card_theme" class="form-select" id="inputCardTheme">
                                <option value="blue" {{ $pengaturan->card_theme == 'blue' ? 'selected' : '' }}>Biru Klasik</option>
                                <option value="dark" {{ $pengaturan->card_theme == 'dark' ? 'selected' : '' }}>Hitam Elegan</option>
                                <option value="gold" {{ $pengaturan->card_theme == 'gold' ? 'selected' : '' }}>Emas Mewah</option>
                                <option value="green" {{ $pengaturan->card_theme == 'green' ? 'selected' : '' }}>Hijau Zamrud</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: E-Wallet & Midtrans -->
            <div class="col-lg-6">
                <!-- E-Wallet Section -->
                <div class="settings-section">
                    <div class="settings-header info">
                        <i class="bi bi-wallet2"></i> Dompet Elektronik / E-Wallet (Opsional)
                    </div>
                    <div class="settings-body">
                        <div class="form-group mb-3">
                            <label>Pilih Dompet Elektronik</label>
                            <input type="text" name="ewallet_name" class="form-control" placeholder="Contoh: GoPay, OVO, DANA" value="{{ $pengaturan->ewallet_name }}">
                        </div>

                        <div class="form-group mb-3">
                            <label>Nomor HP / Dompet Elektronik</label>
                            <input type="text" name="ewallet_phone" class="form-control" placeholder="081234567890" value="{{ $pengaturan->ewallet_phone }}">
                        </div>

                        <div class="form-group mb-0">
                            <label>Atas Nama (A/N)</label>
                            <input type="text" name="ewallet_owner" class="form-control" placeholder="Nama Pemilik E-Wallet" value="{{ $pengaturan->ewallet_owner }}">
                        </div>
                    </div>
                </div>

                <!-- Midtrans Section -->
                <div class="settings-section">
                    <div class="settings-header warning">
                        <i class="bi bi-lightning-charge"></i> Gerbang Pembayaran Otomatis
                    </div>
                    <div class="settings-body">
                        <p class="text-muted small mb-3">Aktifkan untuk menerima pembayaran otomatis via Virtual Account (Midtrans VA/QRIS).</p>
                        
                        <div class="alert alert-danger" style="background-color: #ffe5e5; border: none; color: #d62828;">
                            <i class="bi bi-exclamation-circle-fill me-1"></i> <strong>PENTING:</strong> Pastikan Anda sudah mendaftar Midtrans!
                        </div>

                        <div class="form-check form-switch mb-4" style="padding-left: 2.5em;">
                            <input class="form-check-input" type="checkbox" id="midtransToggle" name="midtrans_is_active" value="1" {{ $pengaturan->midtrans_is_active ? 'checked' : '' }} style="width: 40px; height: 20px; cursor: pointer;">
                            <label class="form-check-label ms-2" for="midtransToggle" style="font-weight: 600; cursor: pointer; padding-top: 2px;">Aktifkan Gateway</label>
                        </div>

                        <div class="border-top pt-3 mt-2">
                            <div class="alert alert-warning" style="background-color: #fff9e6; border: 1px dashed #f6d365; color: #b7791f; font-size: 0.85rem;">
                                <i class="bi bi-info-circle me-1"></i> Masukkan kunci API Midtrans Anda. Jika dibiarkan kosong, pembayaran otomatis tidak akan diproses.
                            </div>

                            <div class="form-group mb-3">
                                <label>Kunci Server Midtrans (Server Key)</label>
                                <input type="text" name="midtrans_server_key" class="form-control" placeholder="SB-Mid-server-xxx" value="{{ $pengaturan->midtrans_server_key }}">
                            </div>

                            <div class="form-group mb-0">
                                <label>Kunci Klien Midtrans (Client Key)</label>
                                <input type="text" name="midtrans_client_key" class="form-control" placeholder="SB-Mid-client-xxx" value="{{ $pengaturan->midtrans_client_key }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Platform Commission Section -->
                <div class="settings-section">
                    <div class="settings-header" style="background-color: #e8f4fd; color: #007bff; border-left: 4px solid #007bff;">
                        <i class="bi bi-percent"></i> Biaya Layanan Platform (Komisi)
                    </div>
                    <div class="settings-body">
                        <p class="text-muted small mb-3">Tentukan persentase potongan komisi yang akan diambil dari setiap transaksi penjualan yang selesai.</p>
                        
                        <div class="form-group mb-0">
                            <label class="fw-bold">Potongan Penjual (%)</label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="biaya_layanan_persen" class="form-control" placeholder="Contoh: 5.0" value="{{ $pengaturan->biaya_layanan_persen ?? 5.00 }}" min="0" max="100">
                                <span class="input-group-text bg-light">%</span>
                            </div>
                            <small class="text-muted mt-1 d-block">Angka ini akan digunakan untuk memotong pendapatan bersih penjual secara otomatis.</small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Live Preview Logic for Card
        const inputBankName = document.getElementById('inputBankName');
        const inputAccountNumber = document.getElementById('inputAccountNumber');
        const inputOwnerName = document.getElementById('inputOwnerName');
        const inputCardTheme = document.getElementById('inputCardTheme');
        
        const previewBankName = document.getElementById('previewBankName');
        const previewAccountNumber = document.getElementById('previewAccountNumber');
        const previewOwnerName = document.getElementById('previewOwnerName');
        const cardPreview = document.getElementById('cardPreview');

        inputBankName.addEventListener('input', function() {
            previewBankName.textContent = this.value || 'BANK / DOMPET ELEKTRONIK';
        });

        inputAccountNumber.addEventListener('input', function() {
            let val = this.value.replace(/\D/g, '');
            if (!val) {
                previewAccountNumber.textContent = '0000 0000 0000 0000';
                return;
            }
            // Add space every 4 digits
            previewAccountNumber.textContent = val.match(/.{1,4}/g).join(' ');
        });

        inputOwnerName.addEventListener('input', function() {
            previewOwnerName.textContent = this.value || 'PEMILIK';
        });

        inputCardTheme.addEventListener('change', function() {
            // Remove existing theme classes
            cardPreview.classList.remove('theme-blue', 'theme-dark', 'theme-gold', 'theme-green');
            // Add new theme class
            cardPreview.classList.add('theme-' + this.value);
        });
    });
</script>
@endsection
