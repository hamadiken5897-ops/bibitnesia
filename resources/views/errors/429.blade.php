@extends('layouts.auth')

@section('title', 'Terlalu Banyak Permintaan')

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
                <h1 class="auth-title text-warning">429</h1>
                <p class="auth-subtitle mb-5">Terlalu Banyak Permintaan.</p>

                <div class="alert alert-warning">
                    <h4 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Mohon Tunggu Sebentar</h4>
                    <p>
                        Demi keamanan akun dan server, kami membatasi jumlah aktivitas dalam waktu singkat. 
                        Anda telah mencapai batas tersebut.
                    </p>
                    <hr>
                    <p class="mb-0">
                        Silakan tunggu sekitar <strong>1 menit</strong> sebelum mencoba kembali.
                    </p>
                </div>

                <a href="{{ url('/') }}" class="btn btn-outline-primary btn-lg mt-5">
                    Kembali ke Beranda
                </a>

            </div>
        </div>

        {{-- BAGIAN KANAN --}}
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right"></div>
        </div>
    </div>
@endsection
