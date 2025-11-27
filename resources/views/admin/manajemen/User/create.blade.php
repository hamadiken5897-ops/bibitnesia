@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <h3>Tambah Akun Pengguna</h3>
        <p class="text-muted">Form untuk menambahkan user baru</p>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>No Telepon</label>
                        <input type="text" name="no_telepon" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Role Pengguna</label>
                        <select name="role" class="form-control" required>
                            <option value="pembeli">Pembeli</option>
                            <option value="penjual">Penjual</option>
                            <option value="kurir">Kurir</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Status Akun</label>
                        <select name="status_akun" class="form-control" required>
                            <option value="AKTIF">Aktif</option>
                            <option value="NONAKTIF">Nonaktif</option>
                            <option value="BANNED">Banned</option>
                        </select>
                    </div>

                </div>

                <button type="submit" class="btn btn-success mt-3">Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-3">Kembali</a>
            </form>

        </div>
    </div>
</section>
@endsection
