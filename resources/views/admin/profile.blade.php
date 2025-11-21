@extends('layouts.admin')

@section('title', 'Profil Admin')
@section('page-title', 'Administrator Profile')
@section('content')

<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            {{-- CARD PROFIL --}}
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">

                    {{-- FOTO + IDENTITAS --}}
                    <div class="text-center">

                        {{-- Foto Profil --}}
                        @if ($user->file)
                            <img src="{{ $user->file->file_stream }}"
                                class="rounded-circle border"
                                style="width: 130px; height: 130px; object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}"
                                class="rounded-circle border"
                                style="width: 130px; height: 130px; object-fit: cover;">
                        @endif

                        {{-- Nama --}}
                        <h2 class="fw-bold mt-3 mb-0">{{ $user->nama }}</h2>

                        {{-- Jabatan --}}
                        <p class="text-muted" style="margin-top:4px;">
                            {{ $user->admin->jabatan_alias ?? 'Tidak ada jabatan' }}
                        </p>

                        {{-- Informasi Users --}}
                        <p class="mt-3">
                            <i class="bi bi-telephone-fill me-2 text-primary"></i>
                            <strong>{{ $user->no_telepon ?? '-' }}</strong>
                        </p>

                        <p>
                            <i class="bi bi-geo-alt-fill me-2 text-danger"></i>
                            <strong>{{ $user->alamat ?? '-' }}</strong>
                        </p>

                        {{-- Tombol Edit --}}
                        <button class="btn btn-primary mt-2" onclick="toggleEdit(true)">
                            <i class="bi bi-pencil-square"></i> Edit Profil
                        </button>
                    </div>


                    {{-- FORM EDIT (DISEMBUNYIKAN AWAL) --}}
                    <div id="profile-edit" style="display:none;" class="mt-4">

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3">Edit Profil</h5>

                        <form action="{{ route('profile.update') }}"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Nama --}}
                            <div class="form-group mb-3">
                                <label class="fw-semibold">Nama</label>
                                <input type="text" name="name" class="form-control"
                                       value="{{ $user->nama }}">
                            </div>

                            {{-- Nomor Telepon --}}
                            <div class="form-group mb-3">
                                <label class="fw-semibold">Nomor Telepon</label>
                                <input type="text" name="no_telepon" class="form-control"
                                       value="{{ $user->no_telepon }}">
                            </div>

                            {{-- Alamat --}}
                            <div class="form-group mb-3">
                                <label class="fw-semibold">Alamat</label>
                                <textarea name="alamat" rows="3"
                                          class="form-control">{{ $user->alamat }}</textarea>
                            </div>

                            {{-- Upload Foto --}}
                            <div class="form-group mb-3">
                                <label class="fw-semibold">Foto Profil Baru</label>
                                <input type="file" name="profile" class="form-control mt-2">
                                <small class="text-muted">Format: JPG, JPEG, PNG | Max 2MB</small>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                                </button>

                                <button type="button" class="btn btn-secondary"
                                        onclick="toggleEdit(false)">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- Script Show/Hide Form --}}
<script>
function toggleEdit(show) {
    document.getElementById('profile-edit').style.display = show ? 'block' : 'none';
}
</script>
@endsection
