@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">

                {{-- LOGO --}}
                <div class="auth-logo">
                    <a href="/">
                        <img src="{{ asset('dist/assets/images/logo/logo bibitnesia.png') }}" alt="Logo"
                            style="width:250px; height:auto;">
                    </a>
                </div>

                {{-- JUDUL & SUBTITLE --}}
                <h1 class="auth-title">Log in.</h1>
                <p class="auth-subtitle mb-5">Grow with us — log in to your account.</p>

                {{-- Notifikasi sukses atau error --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }} {{-- Contoh: "Email atau password salah" --}}
                    </div>
                @endif

                {{-- FORM LOGIN --}}
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="email" name="email" class="form-control form-control-xl" placeholder="Email"
                            required>
                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                    </div>

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" name="password" class="form-control form-control-xl" placeholder="Password"
                            required>
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
                        Log in
                    </button>
                    <a href="{{ route('google.login') }}" class="btn btn-block btn-lg shadow-lg mt-3" style="background-color: white; color: #757575; border: 1px solid #ddd; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 10px;">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google Logo" style="width: 24px; height: 24px;">
                        Log in with Google
                    </a>
                </form>

                {{-- LINK REGISTER & FORGOT PASSWORD --}}
                <div class="text-center mt-5 text-lg fs-4">
                    <p class="text-gray-600">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-bold">Sign up</a>.
                    </p>
                    <p>
                        <a class="font-bold" href="{{ route('password.request') }}">Forgot password?</a>.
                    </p>
                </div>

            </div>
        </div>

        {{-- BAGIAN KANAN --}}
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right"></div>
        </div>
    </div>

    {{-- SWEETALERT OTP --}}
    @if(session('require_otp'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let devOtpMsg = '';
                @if(session('dev_otp_code'))
                    devOtpMsg = '<br><br><span style="color: #0d6efd; font-weight: bold; font-size: 18px;">[Dev Mode] Kode OTP Anda: {{ session("dev_otp_code") }}</span>';
                @endif

                Swal.fire({
                    title: 'Verifikasi OTP',
                    html: 'Masukkan 6 digit kode OTP yang telah dikirim ke email <strong>{{ session("otp_email") }}</strong>' + devOtpMsg,
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
                    footer: '<form id="resendOtpForm" method="POST" action="{{ route("resend.otp") }}"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="email" value="{{ session("otp_email") }}"><button type="submit" class="btn btn-link text-decoration-none" style="font-weight: bold;">Kirim Ulang OTP</button></form>',
                    preConfirm: (otp) => {
                        if (!otp || otp.length !== 6) {
                            Swal.showValidationMessage('Kode OTP harus berisi 6 digit angka');
                            return false;
                        }
                        
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route("verify.otp") }}';
                        
                        let token = document.createElement('input');
                        token.type = 'hidden';
                        token.name = '_token';
                        token.value = '{{ csrf_token() }}';
                        form.appendChild(token);

                        let email = document.createElement('input');
                        email.type = 'hidden';
                        email.name = 'email';
                        email.value = '{{ session("otp_email") }}';
                        form.appendChild(email);

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
