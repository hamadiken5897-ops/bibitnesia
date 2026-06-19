@extends('account.layout')

@section('account_content')
<div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Profil Saya</h4>
        <p class="text-muted mb-0 small">Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun</p>
    </div>
</div>

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
@endsection
