@extends('layouts.admin')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Manajemen Pembayaran</h3>
        <a href="{{ route('admin.pembayaran.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Pembayaran
        </a>
    </div>
</div>

<div class="page-content">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID Pembayaran</th>
                <th>ID Pesanan</th>
                <th>Metode</th>
                <th>Total Bayar</th>
                <th>Tanggal</th>
                <th>Status Validasi</th>
                <th>Tgl Validasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $p)
            <tr>
                <td>{{ $p->id_pembayaran }}</td>
                <td>{{ $p->id_pesanan }}</td>
                <td>{{ $p->metode_pembayaran }}</td>
                <td>{{ number_format($p->total_bayar) }}</td>
                <td>{{ $p->tanggal_pembayaran }}</td>
                <td>{{ $p->status_validasi }}</td>
                <td>{{ $p->tgl_validasi }}</td>
                <td>
                    <a href="{{ route('admin.pembayaran.edit', $p->id_pembayaran) }}" class="btn btn-warning btn-sm">Edit</a>

                    <form action="{{ route('admin.pembayaran.destroy', $p->id_pembayaran) }}" method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
                <tr><td colspan="8" class="text-center">Belum ada data pembayaran</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
