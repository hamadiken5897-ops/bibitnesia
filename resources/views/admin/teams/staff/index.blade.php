@extends('layouts.admin.admin')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Daftar Staff (Admin)</h3>
                <p class="text-subtitle text-muted">Daftar semua akun administrator sistem.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Staff</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible show fade">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Data Staff</h4>
                
                {{-- Hanya Super Admin yang bisa menambah admin baru --}}
                @if(optional(auth()->user()->admin)->jabatan === 'super_admin')
                    <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Admin
                    </a>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>Bergabung</th>
                                <th>Terakhir Login</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staffs as $staff)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $staff->nama }}</td>
                                <td>{{ $staff->email }}</td>
                                <td>
                                    @if(optional($staff->admin)->jabatan === 'super_admin')
                                        <span class="badge bg-danger">Super Admin</span>
                                    @else
                                        <span class="badge bg-primary">Admin</span>
                                    @endif
                                </td>
                                <td>
                                    @if($staff->status_akun === 'AKTIF')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse(optional($staff->admin)->tgl_bergabung)->translatedFormat('d M Y') }}</td>
                                <td>{{ $staff->terakhir_login ? \Carbon\Carbon::parse($staff->terakhir_login)->diffForHumans() : 'Belum pernah' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
