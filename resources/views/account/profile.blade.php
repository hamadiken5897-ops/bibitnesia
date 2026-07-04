@extends('account.layout')

@section('account_content')
<div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Profil Saya</h4>
        <p class="text-muted mb-0 small">Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun</p>
    </div>
</div>

{{-- Koin / Saldo (Hanya Pembeli) --}}
@if ($user->role === 'pembeli')
<div class="card mb-4 border-0 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #f8f9fa, #ffffff);">
    <div class="card-body d-flex justify-content-between align-items-center py-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="fas fa-coins fs-4"></i>
            </div>
            <div>
                <h6 class="mb-0 text-muted fw-bold">Koin / Saldo Bibitnesia</h6>
                <h4 class="mb-0 fw-bold text-dark">Rp {{ number_format($user->saldo, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div>
            <a href="{{ route('user.dompet.index') }}" class="btn btn-outline-success rounded-pill px-4">
                <i class="fas fa-wallet me-1"></i> Tarik Dana
            </a>
        </div>
    </div>
</div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('profile.update.general') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <!-- Form Fields -->
        <div class="col-md-8 pe-md-5 border-end">
            <div class="row mb-3 align-items-center">
                <div class="col-sm-3 text-sm-end text-muted">
                    <label class="mb-0">Nama</label>
                </div>
                <div class="col-sm-9">
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-sm-3 text-sm-end text-muted">
                    <label class="mb-0">Email</label>
                </div>
                <div class="col-sm-9">
                    <div class="d-flex align-items-center">
                        <span>{{ substr($user->email, 0, 2) }}***{{ strstr($user->email, '@') }}</span>
                        <a href="#" class="ms-3 text-decoration-none text-success">Ubah</a>
                    </div>
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-sm-3 text-sm-end text-muted">
                    <label class="mb-0">Nomor Telepon</label>
                </div>
                <div class="col-sm-9">
                    <input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon', $user->no_telepon) }}">
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                    <button type="submit" class="btn btn-success px-4 rounded-3">Simpan</button>
                </div>
            </div>
        </div>

        <!-- Profile Picture Upload -->
        <div class="col-md-4 text-center mt-4 mt-md-0">
            <div class="mb-3 position-relative d-inline-block">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" id="preview-image" class="rounded-circle" width="120" height="120" style="object-fit:cover; border: 3px solid #eee;">
                @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto text-secondary" style="width: 120px; height: 120px; font-size: 40px; border: 3px solid #eee;">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            </div>
            
            <div>
                <label for="profile_upload" class="btn btn-outline-secondary px-3 py-1 bg-white">Pilih Gambar</label>
                <input type="file" name="profile" id="profile_upload" class="d-none" accept=".jpg,.jpeg,.png">
            </div>
            <div class="text-muted mt-3 small text-start px-2">
                Ukuran gambar: maks. 1 MB<br>
                Format gambar: .JPEG, .PNG
            </div>
        </div>
    </div>
</form>

<div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4 mt-5">
    <div>
        <h4 class="fw-bold mb-1">Ubah Password</h4>
        <p class="text-muted mb-0 small">Untuk keamanan akun Anda, mohon tidak menyebarkan password Anda kepada orang lain.</p>
    </div>
</div>

<form action="{{ route('account.password.update') }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-8 pe-md-5">
            @if(empty(Auth::user()->google_id))
            <div class="row mb-3 align-items-center">
                <div class="col-sm-4 text-sm-end text-muted">
                    <label class="mb-0">Password Saat Ini</label>
                </div>
                <div class="col-sm-8">
                    <input type="password" name="current_password" class="form-control" required>
                    @error('current_password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            @endif

            <div class="row mb-3 align-items-center">
                <div class="col-sm-4 text-sm-end text-muted">
                    <label class="mb-0">Password Baru</label>
                </div>
                <div class="col-sm-8">
                    <input type="password" name="password" class="form-control" required>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <div class="col-sm-4 text-sm-end text-muted">
                    <label class="mb-0">Konfirmasi Password Baru</label>
                </div>
                <div class="col-sm-8">
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-sm-4"></div>
                <div class="col-sm-8">
                    <button type="submit" class="btn btn-success px-4 rounded-3">Simpan Password</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    // Simple image preview
    document.getElementById('profile_upload').addEventListener('change', function(e) {
        if(e.target.files && e.target.files[0]) {
            let reader = new FileReader();
            reader.onload = function(ev) {
                let preview = document.getElementById('preview-image');
                if(preview) {
                    preview.src = ev.target.result;
                } else {
                    // Create preview image element if not exists
                    location.reload(); // Simple fallback
                }
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>

{{-- SWEETALERT OTP UNTUK UBAH PASSWORD --}}
@if(session('require_password_otp'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let devOtpMsg = '';
            @if(session('dev_otp_code'))
                devOtpMsg = '<br><br><span style="color: #0d6efd; font-weight: bold; font-size: 18px;">[Dev Mode] Kode OTP Anda: {{ session("dev_otp_code") }}</span>';
            @endif

            Swal.fire({
                title: 'Verifikasi OTP',
                html: 'Masukkan 6 digit kode OTP yang telah dikirim ke email <strong>{{ Auth::user()->email }}</strong> untuk mengonfirmasi perubahan password.' + devOtpMsg,
                input: 'text',
                inputAttributes: {
                    maxlength: 6,
                    autocapitalize: 'off',
                    autocorrect: 'off',
                    style: 'text-align: center; font-size: 24px; letter-spacing: 5px; font-weight: bold;',
                    placeholder: '------'
                },
                showCancelButton: true,
                confirmButtonText: 'Verifikasi',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#198754',
                allowOutsideClick: false,
                preConfirm: (otp) => {
                    if (!otp || otp.length !== 6) {
                        Swal.showValidationMessage('Kode OTP harus berisi 6 digit angka');
                        return false;
                    }
                    
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("account.password.verify") }}';
                    
                    let token = document.createElement('input');
                    token.type = 'hidden';
                    token.name = '_token';
                    token.value = '{{ csrf_token() }}';
                    form.appendChild(token);

                    let otpInput = document.createElement('input');
                    otpInput.type = 'hidden';
                    otpInput.name = 'otp_code';
                    otpInput.value = otp;
                    form.appendChild(otpInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
@endif
@endsection
