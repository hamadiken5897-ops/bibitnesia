@extends('layouts.marketplace.main')

@section('title', 'Profil ' . $user->nama)

@section('content')
<link rel="stylesheet" href="{{ asset('profile_as/css/profile.css') }}">

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Success Message --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- PROFILE CARD --}}
            <div class="profile-card">
                
                {{-- HEADER PROFILE --}}
                <div class="profile-header">
                    <div class="row align-items-center">
                        
                        {{-- Foto Profil --}}
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            @if($user->file)
                                <img src="{{ Storage::url($user->file->path) }}" 
                                     alt="{{ $user->nama }}"
                                     class="profile-avatar">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}&size=200&background=27ae60&color=fff" 
                                     alt="{{ $user->nama }}"
                                     class="profile-avatar">
                            @endif
                        </div>

                        {{-- Info Profile --}}
                        <div class="col-md-9">
                            <div class="profile-info">
                                
                                {{-- Nama + Badge --}}
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <h2 class="profile-name mb-0">{{ $user->nama }}</h2>
                                    
                                    {{-- Badge Role --}}
                                    @if($user->role === 'penjual')
                                        <span class="badge-role badge-penjual">
                                            <i class="bi bi-shop"></i> Penjual
                                        </span>
                                        
                                        {{-- Badge Verifikasi (khusus penjual terverifikasi) --}}
                                        @if($user->penjual && $user->penjual->status_verifikasi === 'Disetujui')
                                            <span class="badge-verified" title="Terverifikasi Admin">
                                                <i class="bi bi-patch-check-fill"></i>
                                            </span>
                                        @endif
                                    @elseif($user->role === 'kurir')
                                        <span class="badge-role badge-kurir">
                                            <i class="bi bi-truck"></i> Kurir
                                        </span>
                                    @else
                                        <span class="badge-role badge-pembeli">
                                            <i class="bi bi-person-fill"></i> Pembeli
                                        </span>
                                    @endif
                                </div>

                                {{-- Rating (khusus penjual) --}}
                                @if($user->role === 'penjual' && $user->penjual)
                                <div class="profile-rating mb-2">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <span class="fw-bold">{{ number_format($user->penjual->rating, 1) }}</span>
                                    <span class="text-muted">/ 5</span>
                                </div>
                                @endif

                                {{-- Contact Info --}}
                                <div class="profile-contact">
                                    @if($user->email)
                                    <div class="contact-item">
                                        <i class="bi bi-envelope-fill"></i>
                                        <span>{{ $user->email }}</span>
                                    </div>
                                    @endif

                                    @if($user->no_telepon)
                                    <div class="contact-item">
                                        <i class="bi bi-telephone-fill"></i>
                                        <span>{{ $user->no_telepon }}</span>
                                    </div>
                                    @endif

                                    @if($user->alamat)
                                    <div class="contact-item">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <span>{{ $user->alamat }}</span>
                                    </div>
                                    @endif
                                </div>

                                {{-- Deskripsi/Bio --}}
                                @if($user->deskripsi)
                                <div class="profile-bio mt-3">
                                    <p class="mb-0">{{ $user->deskripsi }}</p>
                                </div>
                                @else
                                    @if($isOwner)
                                    <div class="profile-bio mt-3">
                                        <p class="text-muted mb-0">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Belum ada deskripsi. Klik "Edit Profil" untuk menambahkan.
                                        </p>
                                    </div>
                                    @endif
                                @endif

                                {{-- Tombol Edit (hanya untuk owner) --}}
                                @if($isOwner)
                                <div class="mt-3">
                                    <button class="btn btn-primary btn-edit" onclick="toggleEditForm()">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Edit Profil
                                    </button>
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

                {{-- FORM EDIT (Hidden by default) --}}
                @if($isOwner)
                <div id="edit-form-container" style="display: none;">
                    <hr class="my-4">
                    
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-pencil-square me-2"></i>Edit Profil
                    </h5>

                    <form action="{{ route('profile.update.general') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Nama --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                                       value="{{ old('nama', $user->nama) }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- No Telepon --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nomor Telepon</label>
                                <input type="text" name="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror" 
                                       value="{{ old('no_telepon', $user->no_telepon) }}" placeholder="Contoh: 08123456789">
                                @error('no_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Alamat --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Alamat</label>
                                <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror" 
                                          placeholder="Alamat lengkap...">{{ old('alamat', $user->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Deskripsi/Bio</label>
                                <textarea name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror" 
                                          placeholder="Ceritakan sedikit tentang diri Anda... (Max 500 karakter)">{{ old('deskripsi', $user->deskripsi) }}</textarea>
                                <small class="text-muted">{{ strlen($user->deskripsi ?? '') }}/500 karakter</small>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Upload Foto --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Foto Profil</label>
                                <input type="file" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror" accept="image/*">
                                <small class="text-muted">Format: JPG, JPEG, PNG | Maksimal 2MB</small>
                                @error('profile_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-3">
                            <button type="button" class="btn btn-secondary" onclick="toggleEditForm()">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                {{-- NAVIGATION TABS --}}
                <div class="profile-tabs mt-4">
                    <ul class="nav nav-tabs border-0" id="profileTabs" role="tablist">
                        
                        {{-- Tab Produk (khusus penjual) --}}
                        @if($user->role === 'penjual')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="produk-tab" data-bs-toggle="tab" 
                                    data-bs-target="#produk" type="button" role="tab">
                                <i class="bi bi-box-seam me-1"></i>Produk
                            </button>
                        </li>
                        @endif

                        {{-- Tab Postingan --}}
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($user->role !== 'penjual') active @endif" 
                                    id="postingan-tab" data-bs-toggle="tab" 
                                    data-bs-target="#postingan" type="button" role="tab">
                                <i class="bi bi-card-text me-1"></i>Postingan
                            </button>
                        </li>

                        {{-- Tab Ulasan (bukan kurir) --}}
                        @if($user->role === 'kurir')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="ulasan-tab" data-bs-toggle="tab" 
                                    data-bs-target="#ulasan" type="button" role="tab">
                                <i class="bi bi-star me-1"></i>Ulasan
                            </button>
                        </li>
                        @endif
                    </ul>

                    {{-- TAB CONTENT --}}
                    <div class="tab-content mt-4" id="profileTabContent">
                        
                        {{-- Content: Produk --}}
                        @if($user->role === 'penjual')
                        <div class="tab-pane fade show active" id="produk" role="tabpanel">
                            @include('profile.components.tab-produk', ['user' => $user, 'isOwner' => $isOwner])
                        </div>
                        @endif

                        {{-- Content: Postingan --}}
                        <div class="tab-pane fade @if($user->role !== 'penjual') show active @endif" 
                             id="postingan" role="tabpanel">
                            @include('profile.components.tab-postingan')
                        </div>

                        {{-- Content: Ulasan --}}
                        <div class="tab-pane fade" id="ulasan" role="tabpanel">
                            @include('profile.components.tab-ulasan')
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="{{ asset('profile_as/js/profile.js') }}"></script>
@endsection