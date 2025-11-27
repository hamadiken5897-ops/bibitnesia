@extends('layouts.admin')

@section('title', 'Detail Pengguna')
@section('page-title', 'Detail Pengguna')

@section('content')

    <div class="page-heading">
        <div class="page-title mb-3">
            <p class="text-muted">Informasi lengkap dari pengguna</p>
        </div>
    </div>

    <section class="section">
        <div class="card shadow-sm p-3">
            <div class="card-body">
                <div class="row">

                    {{-- LEFT: FOTO + INFO UTAMA --}}
                    <div class="col-md-4 text-center border-end">

                        {{-- Foto Profil --}}
                        @if ($user->file)
                            <img src="{{ $user->file->file_stream }}" class="rounded-circle border"
                                style="width: 130px; height: 130px; object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}"
                                class="rounded-circle border" style="width: 130px; height: 130px; object-fit: cover;">
                        @endif


                        {{-- NAMA --}}
                        <h4 class="mt-2">{{ $user->nama }}</h4>

                        {{-- ROLE --}}
                        <span class="badge bg-primary mb-2">
                            {{ strtoupper($user->role) }}
                        </span>

                        {{-- STATUS AKUN --}}
                        <div class="mt-1">
                            @if ($user->status_akun === 'AKTIF')
                                <span class="badge bg-success">Aktif</span>
                            @elseif ($user->status_akun === 'NONAKTIF')
                                <span class="badge bg-warning">Nonaktif</span>
                            @else
                                <span class="badge bg-danger">Banned</span>
                            @endif
                        </div>

                    </div>

                    {{-- RIGHT: INFORMASI LENGKAP --}}
                    <div class="col-md-8">

                        <h5 class="mb-3">Informasi Detail</h5>

                        <table class="table table-borderless">

                            <tr>
                                <th width="30%">Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>

                            <tr>
                                <th>No Telepon</th>
                                <td>{{ $user->no_telepon ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Alamat</th>
                                <td>{{ $user->alamat ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Tanggal Daftar</th>
                                <td>{{ $user->tanggal_daftar }}</td>
                            </tr>

                            <tr>
                                <th>Terakhir Login</th>
                                <td>{{ $user->terakhir_login ?? '-' }}</td>
                            </tr>

                        </table>

                        <a href="{{ route('admin.users.edit', $user->id_user) }}" class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i> Edit Profil
                        </a>

                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>

                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection
