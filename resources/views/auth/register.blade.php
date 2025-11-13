@extends('layouts.auth')

@section('title', 'Register')

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
                <h1 class="auth-title">Sign Up.</h1>
                <p class="auth-subtitle mb-5">Create your account to start growing with us..</p>


                {{-- Notifikasi --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                {{-- FORM REGISTER --}}
                <form method="POST" action="{{ route('register.post') }}">
                    @csrf
                    <div class="form-group position-relative has-icon-left mb-3">
                        <input type="text" name="nama" class="form-control form-control-xl" placeholder="Full Name"
                            required>
                        <div class="form-control-icon">
                            <i class="bi bi-person"></i>
                        </div>
                    </div>

                    <div class="form-group position-relative has-icon-left mb-3">
                        <input type="email" name="email" class="form-control form-control-xl"
                            placeholder="Email Address" required>
                        <div class="form-control-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>

                    <div class="form-group position-relative has-icon-left mb-3">
                        <input type="text" name="no_telepon" class="form-control form-control-xl"
                            placeholder="Phone Number" required>
                        <div class="form-control-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                    </div>

                    <div class="form-group position-relative has-icon-left mb-3">
                        <input type="password" name="password" class="form-control form-control-xl" placeholder="Password"
                            required>
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>

                    <div class="form-group position-relative has-icon-left mb-4">
                        <input type="password" name="password_confirmation" class="form-control form-control-xl"
                            placeholder="Confirm Password" required>
                        <div class="form-control-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg mt-4">
                        Sign Up
                    </button>
                </form>

                {{-- LINK LOGIN --}}
                <div class="text-center mt-4 text-lg fs-6">
                    <p class="text-gray-600 mb-1">
                        Already have an account?
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
