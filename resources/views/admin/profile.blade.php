@extends('layouts.admin')
@section('title', 'Profile - BibitNesia Admin')
@section('page-title', 'Profile Admin')

@section('content')

    <div class="page-content">
        <section class="row">
            <div class="col-md-10">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>Administrator Profile</h4>
                    </div>
                    <div class="card-body">
                        {{-- Foto Profil --}}
                        <div class="form-group mt-3 text-center">

                            @if ($user->file)
                                <img src="{{ $user->file->file_stream }}" class="rounded-circle mb-3 border"
                                    style="width: 130px; height: 130px; object-fit: cover;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}"
                                    class="rounded-circle mb-3 border"
                                    style="width: 130px; height: 130px; object-fit: cover;">
                            @endif

                            {{-- Nama & Jabatan --}}
                            <div class="text-center mt-2">
                                <h2 class="fw-bold mb-0">{{ $user->nama }}</h2>
                                <h5 class="text-muted" style="margin-top: 4px;">
                                    {{ $user->admin->jabatan_alias ?? 'Tidak ada jabatan' }}
                                </h5>
                            </div>
                        </div>


                        {{-- Informasi Profil (Hanya Tampilan Baca) --}}
                        <div id="profile-view">
                            <p><strong>Nama:</strong> {{ $user->nama }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p class="mt-2"><strong>Nomor Telepon:</strong> {{ $user->no_telepon ?? '-' }}</p>
                            <p><strong>Alamat:</strong> {{ $user->alamat ?? '-' }}</p>

                            <button class="btn btn-primary mt-2" onclick="toggleEdit(true)">
                                Edit Profile
                            </button>
                        </div>

                        {{-- Form Edit Profil (Tersembunyi Awal) --}}
                        <div id="profile-edit" style="display: none;">
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                                class="mt-3">
                                @csrf
                                @method('PUT')

                                {{-- Input Nama --}}
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" name="name" value="{{ $user->name }}" class="form-control">
                                </div>

                                {{-- Input Nomor Telepon --}}
                                <div class="form-group mt-3">
                                    <label>Nomor Telepon</label>
                                    <input type="text" name="no_telepon" value="{{ $user->no_telepon }}"
                                        class="form-control">
                                </div>

                                {{-- Input Alamat --}}
                                <div class="form-group mt-3">
                                    <label>Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3">{{ $user->alamat }}</textarea>
                                </div>

                                {{-- Input Foto --}}
                                <div class="form-group mt-3">
                                    <label>Foto Baru (opsional)</label>
                                    <input type="file" name="profile" class="form-control mt-2">
                                    <small>Format: JPG, JPEG, PNG | Max: 2MB</small>
                                </div>

                                <button type="submit" class="btn btn-success mt-3">Simpan</button>
                                <button type="button" class="btn btn-secondary mt-3"
                                    onclick="toggleEdit(false)">Batal</button>
                            </form>
                        </div>

                        {{-- Script untuk Toggle Edit --}}
                        <script>
                            function toggleEdit(show) {
                                document.getElementById('profile-view').style.display = show ? 'none' : 'block';
                                document.getElementById('profile-edit').style.display = show ? 'block' : 'none';
                            }
                        </script>


                        <button class="btn btn-primary mt-3">Simpan</button>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
