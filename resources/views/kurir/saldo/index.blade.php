@extends('layouts.kurir.kurir')

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
        border-color: #4361ee !important;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
    }
    
    /* Custom Radio Option Cards */
    .option-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }
    
    .option-card {
        position: relative;
        display: block;
        cursor: pointer;
        margin-bottom: 0;
    }
    
    .option-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    
    .option-card .card-content {
        border: 2px solid #eef2f7;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        transition: all 0.2s ease;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 60px;
    }
    
    .option-card .card-content img {
        max-width: 80%;
        max-height: 35px;
        object-fit: contain;
        transition: all 0.2s ease;
    }
    
    .option-card input[type="radio"]:checked ~ .card-content {
        border-color: #4361ee !important;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        transform: translateY(-2px);
    }
    .option-card input[type="radio"]:not(:checked) ~ .card-content img {
        filter: grayscale(100%) opacity(0.6);
    }
    .tarik-option {
        display: flex;
        align-items: center;
        gap: 15px;
        border: 2px solid #eef2f7;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .tarik-option input[type="radio"] {
        position: absolute; opacity: 0; pointer-events: none;
    }
    .tarik-option input[type="radio"]:checked ~ .tarik-content {
        border-color: #4361ee !important;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        background: #f8faff !important;
    }
    .tarik-content {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 2px solid #dee2e6 !important;
        border-radius: 0.375rem !important;
        padding: 1rem !important;
        transition: all 0.2s ease;
    }
    
    .tarik-logo-img {
        width: 60px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    
    .tarik-logo-img img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .saldo-card-modal {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        border-radius: 12px;
        padding: 24px;
        color: white;
        box-shadow: 0 10px 20px rgba(67, 97, 238, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    .saldo-card-modal::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
    }
    
    .saldo-card-modal .icon-circle {
        width: 48px;
        height: 48px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
</style>
<div class="container">

    <h4 class="mb-4 fw-bold">Saldo & Keuangan</h4>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        {{-- Card Saldo Aktif & Tarik Saldo --}}
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                <div class="card-body py-4 d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="text-white-50 text-uppercase fw-bold mb-1">Saldo Aktif Bisa Ditarik</h6>
                        <h2 class="mb-0 text-white fw-bold">Rp {{ number_format($kurir->saldo, 0, ',', '.') }}</h2>
                    </div>
                    <div class="mt-4">
                        @php
                            $hasBank = !empty($kurir->nama_bank) && !empty($kurir->no_rekening);
                            $hasEwallet = !empty($kurir->ewallet_name) && !empty($kurir->ewallet_phone);
                        @endphp
                        
                        @if(!$hasBank && !$hasEwallet)
                            <button class="btn btn-light fw-bold" disabled>
                                <i class="bi bi-wallet2 me-1"></i> Set Rekening Dulu
                            </button>
                        @elseif($kurir->saldo < 10000)
                            <button class="btn btn-light fw-bold" disabled>
                                <i class="bi bi-wallet2 me-1"></i> Minimal Tarik Rp 10.000
                            </button>
                        @else
                            <button class="btn btn-light text-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalTarikSaldo">
                                <i class="bi bi-wallet2 me-1"></i> Tarik Saldo
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Statistik --}}
        <div class="col-md-6 mb-3">
            <div class="row h-100">
                <div class="col-12 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted text-uppercase fw-bold">Total Pendapatan (Sepanjang Masa)</small>
                            <h4 class="fw-bold text-dark mt-1">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <small class="text-muted text-uppercase fw-bold">Paket Selesai Dikirim</small>
                            <h4 class="fw-bold text-dark mt-1">{{ $total_pengiriman }} Pesanan</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Pengaturan Rekening --}}
        <div class="col-lg-5 mb-4">
            <form action="{{ route('kurir.rekening.update') }}" method="POST" id="rekening-form">
                @csrf
                
                {{-- Bagian Bank --}}
                <div class="settings-section">
                    <div class="settings-header primary">
                        <i class="bi bi-bank"></i> Rekening Bank Utama
                    </div>
                    
                    <div class="settings-body">
                        <!-- Card Preview -->
                        <div class="payment-card-preview theme-blue" id="cardPreview">
                            <div class="card-chip"></div>
                            <div class="card-bank-name" id="previewBankName">
                                {{ $kurir->nama_bank ?: 'BANK / DOMPET ELEKTRONIK' }}
                            </div>
                            <div class="card-number" id="previewAccountNumber">
                                {{ $kurir->no_rekening ? implode(' ', str_split(str_pad($kurir->no_rekening, 16, '0'), 4)) : '0000 0000 0000 0000' }}
                            </div>
                            <div class="card-owner-label">Atas Nama</div>
                            <div class="card-owner-name" id="previewOwnerName">
                                {{ $kurir->nama_pemilik_rekening ?: 'PEMILIK' }}
                            </div>
                            <div class="card-contactless">
                                <i class="bi bi-wifi" style="transform: rotate(90deg); display: inline-block;"></i>
                            </div>
                        </div>

                        <div class="form-group mb-3 mt-4">
                            <label>Pilih Bank</label>
                            <div class="option-grid">
                                <label class="option-card">
                                    <input type="radio" name="nama_bank" value="BCA" {{ $kurir->nama_bank == 'BCA' ? 'checked' : '' }}>
                                    <div class="card-content">
                                        <img src="{{ asset('img/banks/bca.png') }}" alt="BCA">
                                    </div>
                                </label>
                                <label class="option-card">
                                    <input type="radio" name="nama_bank" value="MANDIRI" {{ $kurir->nama_bank == 'MANDIRI' ? 'checked' : '' }}>
                                    <div class="card-content">
                                        <img src="{{ asset('img/banks/mandiri.png') }}" alt="MANDIRI">
                                    </div>
                                </label>
                                <label class="option-card">
                                    <input type="radio" name="nama_bank" value="BNI" {{ $kurir->nama_bank == 'BNI' ? 'checked' : '' }}>
                                    <div class="card-content">
                                        <img src="{{ asset('img/banks/bni.png') }}" alt="BNI">
                                    </div>
                                </label>
                                <label class="option-card">
                                    <input type="radio" name="nama_bank" value="BRI" {{ $kurir->nama_bank == 'BRI' ? 'checked' : '' }}>
                                    <div class="card-content">
                                        <img src="{{ asset('img/banks/bri.png') }}" alt="BRI">
                                    </div>
                                </label>
                                <label class="option-card">
                                    <input type="radio" name="nama_bank" value="BSI" {{ $kurir->nama_bank == 'BSI' ? 'checked' : '' }}>
                                    <div class="card-content">
                                        <img src="{{ asset('img/banks/bsi.png') }}" alt="BSI">
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Nomor Rekening</label>
                            <input type="text" name="no_rekening" class="form-control" value="{{ $kurir->no_rekening }}" placeholder="0000000000" id="inputAccountNumber">
                        </div>
                        <div class="form-group mb-0">
                            <label>Atas Nama (A/N)</label>
                            <input type="text" name="nama_pemilik_rekening" class="form-control" value="{{ $kurir->nama_pemilik_rekening }}" placeholder="Nama Pemilik Rekening" id="inputOwnerName">
                        </div>
                    </div>
                </div>

                {{-- Bagian E-Wallet --}}
                <div class="settings-section">
                    <div class="settings-header info">
                        <i class="bi bi-wallet2"></i> Dompet Elektronik / E-Wallet (Opsional)
                    </div>
                    <div class="settings-body">
                        <div class="form-group mb-3">
                            <label>Pilih Dompet Elektronik</label>
                            <div class="option-grid">
                                <label class="option-card">
                                    <input type="radio" name="ewallet_name" value="GOPAY" {{ $kurir->ewallet_name == 'GOPAY' ? 'checked' : '' }}>
                                    <div class="card-content">
                                        <img src="{{ asset('img/banks/gopay.png') }}" alt="GoPay">
                                    </div>
                                </label>
                                <label class="option-card">
                                    <input type="radio" name="ewallet_name" value="OVO" {{ $kurir->ewallet_name == 'OVO' ? 'checked' : '' }}>
                                    <div class="card-content">
                                        <img src="{{ asset('img/banks/ovo.png') }}" alt="OVO">
                                    </div>
                                </label>
                                <label class="option-card">
                                    <input type="radio" name="ewallet_name" value="DANA" {{ $kurir->ewallet_name == 'DANA' ? 'checked' : '' }}>
                                    <div class="card-content">
                                        <img src="{{ asset('img/banks/dana.png') }}" alt="DANA">
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Nomor HP / Dompet Elektronik</label>
                            <input type="text" name="ewallet_phone" class="form-control" placeholder="081234567890" value="{{ $kurir->ewallet_phone }}">
                        </div>
                        <div class="form-group mb-0">
                            <label>Atas Nama (A/N)</label>
                            <input type="text" name="ewallet_owner" class="form-control" placeholder="Nama Pemilik E-Wallet" value="{{ $kurir->ewallet_owner }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm fw-bold"><i class="bi bi-save me-2"></i> Simpan Pengaturan</button>
            </form>
        </div>

        {{-- Tabel Riwayat --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="historyTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="penarikan-tab" data-bs-toggle="tab" data-bs-target="#penarikan" type="button" role="tab">Riwayat Penarikan</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pemasukan-tab" data-bs-toggle="tab" data-bs-target="#pemasukan" type="button" role="tab">Riwayat Pemasukan</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="historyTabContent">
                        
                        {{-- Tab Penarikan --}}
                        <div class="tab-pane fade show active" id="penarikan" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jumlah Tarik</th>
                                            <th>Bank Tujuan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($riwayatPenarikan as $tarik)
                                            <tr>
                                                <td>{{ $tarik->tgl_pengajuan->format('d M Y H:i') }}</td>
                                                <td class="text-danger fw-bold">- Rp {{ number_format($tarik->jumlah_penarikan, 0, ',', '.') }}</td>
                                                <td>{{ strtoupper($tarik->nama_bank) }} <br><small class="text-muted">{{ $tarik->no_rekening }}</small></td>
                                                <td>
                                                    @if ($tarik->status === 'selesai')
                                                        <span class="badge bg-success">Selesai</span>
                                                    @elseif ($tarik->status === 'pending')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @elseif ($tarik->status === 'diproses')
                                                        <span class="badge bg-info">Diproses</span>
                                                    @else
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">Belum ada riwayat penarikan saldo.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2">{{ $riwayatPenarikan->links() }}</div>
                        </div>

                        {{-- Tab Pemasukan --}}
                        <div class="tab-pane fade" id="pemasukan" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Pemasukan</th>
                                            <th>Tanggal</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($laporan as $row)
                                            <tr>
                                                <td class="fw-bold">#{{ $row->id_pesanan }}</td>
                                                <td class="text-success fw-bold">+ Rp {{ number_format($row->total_jumlah, 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($row->tgl_masuk)->format('d M Y H:i') }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $row->id_pesanan }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <!-- Modal Detail (Hidden for brevity, but functional because it's at end of file) -->
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">Belum ada pemasukan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2">{{ $laporan->links() }}</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- MODAL TARIK SALDO --}}
<div class="modal fade" id="modalTarikSaldo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tarik Saldo Ke Rekening</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kurir.saldo.tarik') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="saldo-card-modal mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 text-white-50">Saldo Aktif Anda:</h6>
                                <h3 class="mb-0 fw-bold text-white">Rp {{ number_format($kurir->saldo, 0, ',', '.') }}</h3>
                            </div>
                            <div class="icon-circle">
                                <i class="bi bi-wallet2"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih Tujuan Penarikan</label>
                        
                        @if($hasBank)
                        <label class="tarik-option w-100 mb-2">
                            <input type="radio" name="tujuan_penarikan" value="bank" checked>
                            <div class="tarik-content">
                                <div>
                                    <div class="fw-bold mb-1"><i class="bi bi-bank text-primary me-2"></i>Rekening Bank</div>
                                    <div class="text-muted small">{{ $kurir->no_rekening }} (a/n {{ $kurir->nama_pemilik_rekening }})</div>
                                </div>
                                <div class="tarik-logo-img">
                                    <img src="{{ asset('img/banks/' . strtolower($kurir->nama_bank) . '.png') }}" alt="{{ $kurir->nama_bank }}">
                                </div>
                            </div>
                        </label>
                        @endif

                        @if($hasEwallet)
                        <label class="tarik-option w-100 mb-2">
                            <input type="radio" name="tujuan_penarikan" value="ewallet" {{ !$hasBank ? 'checked' : '' }}>
                            <div class="tarik-content">
                                <div>
                                    <div class="fw-bold mb-1"><i class="bi bi-wallet2 text-info me-2"></i>E-Wallet</div>
                                    <div class="text-muted small">{{ $kurir->ewallet_phone }} (a/n {{ $kurir->ewallet_owner }})</div>
                                </div>
                                <div class="tarik-logo-img">
                                    <img src="{{ asset('img/banks/' . strtolower($kurir->ewallet_name) . '.png') }}" alt="{{ $kurir->ewallet_name }}">
                                </div>
                            </div>
                        </label>
                        @endif
                        
                        <small class="text-muted d-block mt-2"><i class="bi bi-info-circle me-1"></i> Untuk mengubah rekening, edit melalui form Pengaturan Rekening.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nominal Penarikan (Rp)</label>
                        <input type="number" name="jumlah_penarikan" class="form-control form-control-lg" max="{{ $kurir->saldo }}" min="10000" placeholder="Minimal Rp 10.000" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-bold"><i class="bi bi-wallet2 me-1"></i> Ajukan Penarikan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DETAIL PEMASUKAN (Old loop reinstated) --}}
@foreach($laporan as $row)
<div class="modal fade" id="modalDetail{{ $row->id_pesanan }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pemasukan #{{ $row->id_pesanan }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    @if(isset($pengirimanDetails[$row->id_pesanan]))
                        @foreach($pengirimanDetails[$row->id_pesanan]->pesanan->detailPesanan as $detail)
                            @if($detail->produk)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <h6 class="mb-0">{{ $detail->produk->nama_produk }}</h6>
                                    <small class="text-muted">Ongkir dari Pesanan #{{ $row->id_pesanan }}</small>
                                </div>
                                <strong class="text-success">Rp {{ number_format($detail->ongkir ?? 15000, 0, ',', '.') }}</strong>
                            </li>
                            @break <!-- Only show one ongkir since it's per order -->
                            @endif
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <span class="fw-bold">Total:</span>
                <span class="fw-bold text-success">Rp {{ number_format($row->total_jumlah, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Live Preview Logic for Card
    const inputBankRadios = document.querySelectorAll('input[name="nama_bank"]');
    const inputAccountNumber = document.getElementById('inputAccountNumber');
    const inputOwnerName = document.getElementById('inputOwnerName');
    
    const previewBankName = document.getElementById('previewBankName');
    const previewAccountNumber = document.getElementById('previewAccountNumber');
    const previewOwnerName = document.getElementById('previewOwnerName');

    if(inputBankRadios.length > 0) {
        inputBankRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                previewBankName.textContent = this.value || 'BANK / DOMPET ELEKTRONIK';
            });
        });
    }

    if(inputAccountNumber) {
        inputAccountNumber.addEventListener('input', function() {
            let val = this.value.replace(/\D/g, '');
            if (!val) {
                previewAccountNumber.textContent = '0000 0000 0000 0000';
                return;
            }
            // Add space every 4 digits
            previewAccountNumber.textContent = val.match(/.{1,4}/g).join(' ');
        });
    }

    if(inputOwnerName) {
        inputOwnerName.addEventListener('input', function() {
            previewOwnerName.textContent = this.value || 'PEMILIK';
        });
    }
    });
</script>

@endsection



