@extends('layouts.marketplace.main')

@section('content')
<div class="container mt-4 mb-5">
    @if(auth()->user()->peringatan_teks && auth()->user()->tgl_peringatan && \Carbon\Carbon::parse(auth()->user()->tgl_peringatan)->addDays(5)->isFuture())
        <div class="alert alert-warning alert-dismissible fade show shadow-sm rounded-4 mb-4" role="alert" style="background-color: #fff8e1; color: #d35400; font-weight: 500; border: 1px solid #ffeaa7;">
            <i class="bi bi-exclamation-triangle-fill me-2" style="color: #f39c12;"></i> <strong>Peringatan Admin:</strong> {{ auth()->user()->peringatan_teks }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row">
        <!-- Account Sidebar -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 text-center">
                    @if(auth()->user()->profile_image)
                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" class="rounded-circle mb-3" width="80" height="80" style="object-fit:cover; border: 2px solid #27ae60;">
                    @else
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 32px; font-weight:bold;">
                            {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                        </div>
                    @endif
                    <h6 class="fw-bold mb-1">{{ auth()->user()->nama }}</h6>
                    <p class="text-muted small mb-0"><i class="fas fa-pen"></i> Ubah Profil</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush rounded-4">
                        <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action py-3 px-4 border-0 {{ request()->routeIs('account.profile') ? 'text-success fw-bold' : '' }}">
                            <i class="fas fa-user-circle me-2"></i> Profil Saya
                        </a>
                        <a href="{{ route('account.alamat') }}" class="list-group-item list-group-item-action py-3 px-4 border-0 {{ request()->routeIs('account.alamat') ? 'text-success fw-bold' : '' }}">
                            <i class="fas fa-map-marker-alt me-2"></i> Alamat
                        </a>
                        <a href="{{ route('account.cs') }}" class="list-group-item list-group-item-action py-3 px-4 border-0 {{ request()->routeIs('account.cs') ? 'text-success fw-bold' : '' }}">
                            <i class="fas fa-headset me-2"></i> Customer Service
                        </a>
                        <a href="#" class="list-group-item list-group-item-action py-3 px-4 border-0">
                            <i class="fas fa-lock me-2"></i> Ubah Password
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Content -->
        <div class="col-md-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    @yield('account_content')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
