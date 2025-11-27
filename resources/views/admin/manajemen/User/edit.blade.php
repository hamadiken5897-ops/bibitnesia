@extends('layouts.admin')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <h3>Edit Akun Pengguna</h3>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.users.update', $user->id_user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>ID User</label>
                        <input type="text" value="{{ $user->id_user }}" class="form-control" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" value="{{ $user->nama }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Password (opsional)</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>No Telepon</label>
                        <input type="text" name="no_telepon" class="form-control" value="{{ $user->no_telepon }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control">{{ $user->alamat }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Role</label>
                        <select name="role" class="form-control">
                            <option value="pembeli" {{ $user->role == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                            <option value="penjual" {{ $user->role == 'penjual' ? 'selected' : '' }}>Penjual</option>
                            <option value="kurir" {{ $user->role == 'kurir' ? 'selected' : '' }}>Kurir</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Status Akun</label>
                        <select name="status_akun" class="form-control">
                            <option value="AKTIF" {{ $user->status_akun == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                            <option value="NONAKTIF" {{ $user->status_akun == 'NONAKTIF' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="BANNED" {{ $user->status_akun == 'BANNED' ? 'selected' : '' }}>Banned</option>
                        </select>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary mt-3">Update</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-3">Kembali</a>

            </form>

        </div>
    </div>
</section>
@endsection
