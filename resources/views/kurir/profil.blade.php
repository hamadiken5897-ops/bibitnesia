@extends('layouts.kurir.kurir')

@section('title', 'Profil Kurir')
@section('page-title', 'Profil Kurir')
@section('content')

@php
    $user = auth()->user();
@endphp

<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">

                    <div class="text-center">

                        {{-- Foto Profil --}}
                        @if (!empty($user->file) && !empty($user->file->file_stream))
                            <img src="{{ $user->file->file_stream }}"
                                class="rounded-circle border"
                                style="width:130px;height:130px;object-fit:cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->nama) }}"
                                class="rounded-circle border"
                                style="width:130px;height:130px;object-fit:cover;">
                        @endif

                        {{-- ✅ NAMA (SUDAH BENAR) --}}
                        <h2 class="fw-bold mt-3 mb-0">{{ $user->nama }}</h2>

                        {{-- Role --}}
                        <p class="text-muted">{{ $user->role ?? 'Kurir' }}</p>

                        {{-- Informasi --}}
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


                    {{-- FORM EDIT --}}
                    <div id="profile-edit" style="display:none;" class="mt-4">
                        <hr class="my-4">
                        <h5 class="fw-bold mb-3">Edit Profil Kurir</h5>

                        <form action="{{ route('kurir.profil.update') }}"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- ✅ Nama --}}
                            <div class="form-group mb-3">
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control"
                                       value="{{ $user->nama }}">
                            </div>

                            {{-- No Telepon --}}
                            <div class="form-group mb-3">
                                <label>No Telepon</label>
                                <input type="text" name="no_telepon" class="form-control"
                                       value="{{ $user->no_telepon }}">
                            </div>

                            {{-- Alamat --}}
                            <div class="form-group mb-3">
                                <label>Alamat</label>
                                <textarea name="alamat" rows="3"
                                          class="form-control">{{ $user->alamat }}</textarea>
                            </div>

                            {{-- Upload Foto --}}
                            <div class="form-group mb-3">
                                <label>Foto Profil</label>
                                <input type="file" name="profile" class="form-control"
                                       accept="image/png, image/jpeg, image/jpg">
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">
                                    Simpan
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

<script>
function toggleEdit(show) {
    document.getElementById('profile-edit').style.display = show ? 'block' : 'none';
}
</script>
@endsection
