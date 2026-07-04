@extends('layouts.marketplace.main')

@section('title', 'Dompet / Saldo Bibitnesia')

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

    /* Custom Radio Option Cards */
    .option-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
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
</style>


<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0"><i class="bi bi-wallet2 text-success me-2"></i>Dompet / Saldo Saya</h3>
                <a href="{{ route('account.profile') }}" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>

            {{-- Card Saldo --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; background: linear-gradient(135deg, #27ae60, #2ecc71);">
                <div class="card-body text-white text-center py-4">
                    <h5 class="card-title fw-light mb-1">Total Saldo (Koin)</h5>
                    <h1 class="display-5 fw-bold mb-3">Rp {{ number_format($user->saldo, 0, ',', '.') }}</h1>
                    
                    <button class="btn btn-light btn-sm fw-bold px-4 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                        <i class="bi bi-cash-stack me-1"></i> Tarik Dana
                    </button>
                </div>
            </div>



            {{-- Riwayat Transaksi --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="bi bi-clock-history me-2"></i>Riwayat Transaksi</h5>
                </div>
                <div class="card-body">
                    @if($histories->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-1"></i>
                            <p class="mt-2">Belum ada riwayat transaksi.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($histories as $history)
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold">{{ $history->deskripsi }}</div>
                                        <small class="text-muted">{{ $history->created_at->translatedFormat('d M Y, H:i') }}</small>
                                    </div>
                                    <div class="text-end">
                                        @if($history->tipe == 'masuk')
                                            <span class="text-success fw-bold">+ Rp {{ number_format($history->jumlah, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-danger fw-bold">- Rp {{ number_format($history->jumlah, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal Withdraw --}}
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-cash-stack me-2 text-success"></i>Tarik Dana Saldo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.dompet.withdraw') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3 form-group">
                        <label class="form-label fw-semibold">Pilih Bank / E-Wallet</label>
                        <div class="option-grid" style="grid-template-columns: repeat(4, 1fr);">
                            <!-- Banks -->
                            <label class="option-card">
                                <input type="radio" name="nama_bank" value="BCA" checked required>
                                <div class="card-content"><img src="{{ asset('img/banks/bca.png') }}" alt="BCA"></div>
                            </label>
                            <label class="option-card">
                                <input type="radio" name="nama_bank" value="MANDIRI">
                                <div class="card-content"><img src="{{ asset('img/banks/mandiri.png') }}" alt="MANDIRI"></div>
                            </label>
                            <label class="option-card">
                                <input type="radio" name="nama_bank" value="BNI">
                                <div class="card-content"><img src="{{ asset('img/banks/bni.png') }}" alt="BNI"></div>
                            </label>
                            <label class="option-card">
                                <input type="radio" name="nama_bank" value="BRI">
                                <div class="card-content"><img src="{{ asset('img/banks/bri.png') }}" alt="BRI"></div>
                            </label>
                            <label class="option-card">
                                <input type="radio" name="nama_bank" value="BSI">
                                <div class="card-content"><img src="{{ asset('img/banks/bsi.png') }}" alt="BSI"></div>
                            </label>
                            <!-- E-Wallets -->
                            <label class="option-card">
                                <input type="radio" name="nama_bank" value="GOPAY">
                                <div class="card-content"><img src="{{ asset('img/banks/gopay.png') }}" alt="GoPay"></div>
                            </label>
                            <label class="option-card">
                                <input type="radio" name="nama_bank" value="OVO">
                                <div class="card-content"><img src="{{ asset('img/banks/ovo.png') }}" alt="OVO"></div>
                            </label>
                            <label class="option-card">
                                <input type="radio" name="nama_bank" value="DANA">
                                <div class="card-content"><img src="{{ asset('img/banks/dana.png') }}" alt="DANA"></div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3 form-group">
                        <label class="form-label fw-semibold">Nomor Rekening / No. HP</label>
                        <input type="text" name="no_rekening" class="form-control" placeholder="0000000000" required>
                    </div>

                    <div class="mb-3 form-group">
                        <label class="form-label fw-semibold">Nama Pemilik (A/N)</label>
                        <input type="text" name="nama_pemilik_rekening" class="form-control" placeholder="Sesuai buku tabungan / aplikasi" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Penarikan (Rp)</label>
                        <input type="number" name="jumlah" class="form-control form-control-lg text-center fw-bold" required min="10000" max="{{ (int) $user->saldo }}" placeholder="Minimal Rp 10.000">
                        <div class="form-text text-danger text-center mt-2" style="display: none;" id="error-jumlah">Saldo tidak mencukupi!</div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 justify-content-center">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">Ajukan Penarikan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputJumlah = document.querySelector('input[name="jumlah"]');
        if (inputJumlah) {
            const maxSaldo = {{ (int) $user->saldo }};
            const errorText = document.getElementById('error-jumlah');
            const btnSubmit = document.querySelector('#withdrawModal button[type="submit"]');

            inputJumlah.addEventListener('input', function() {
                if (parseInt(this.value) > maxSaldo) {
                    errorText.style.display = 'block';
                    btnSubmit.disabled = true;
                } else {
                    errorText.style.display = 'none';
                    btnSubmit.disabled = false;
                }
            });
        }
    });
</script>
@endsection
