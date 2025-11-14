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
@endsection
