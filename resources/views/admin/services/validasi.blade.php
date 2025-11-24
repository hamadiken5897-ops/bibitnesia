@extends('layouts.admin')

@section('title', 'Antrian Validasi Banned')

@section('content')
<div class="page-heading">
    <h3>Antrian Validasi Banned</h3>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Banned</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Tgl Banned</th>
                        <th>Tgl Berakhir</th>
                        <th>Alasan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banneds as $ban)
                        <tr>
                            <td>{{ $ban->id_banned }}</td>
                            <td>{{ $ban->user->nama ?? $ban->id_user }}</td>
                            <td>{{ $ban->status }}</td>
                            <td>{{ $ban->tgl_banned }}</td>
                            <td>{{ $ban->tgl_berakhir }}</td>
                            <td>{{ $ban->alasan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data untuk divalidasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
