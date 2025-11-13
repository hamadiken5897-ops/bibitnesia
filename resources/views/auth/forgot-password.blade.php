@extends('layouts.auth')

@section('title', 'Forgot Password')

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
                <h1 class="auth-title">Forgot Password.</h1>
                <p class="auth-subtitle mb-5">Enter your email and we'll send you a link to reset your password.</p>

                {{-- FORM FORGOT PASSWORD --}}
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="email" name="email" class="form-control form-control-xl" placeholder="Email"
                            required>
                        <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
                        Send Reset Link
                    </button>
                </form>

                {{-- LINK LOGIN --}}
                <div class="text-center mt-5 text-lg fs-4">
                    <p class="text-gray-600">
                        Remember your password?
                        <a href="{{ route('login') }}" class="font-bold">Log in</a>.
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
