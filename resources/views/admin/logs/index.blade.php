@extends('layouts.admin.admin')

@section('title', 'Log Aktivitas Admin')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Log Aktivitas Admin</h3>
    </div>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Daftar Log Aktivitas</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Admin</th>
                            <th>Aksi</th>
                            <th>Tabel/Modul</th>
                            <th>Detail</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                            <td>
                                @if($log->user)
                                    {{ $log->user->nama }} <br>
                                    <small class="text-muted">{{ optional($log->user->admin)->jabatan ?? 'User' }}</small>
                                @else
                                    <span class="text-danger">Sistem / Dihapus</span>
                                @endif
                            </td>
                            <td>
                                @if($log->action === 'login' || $log->action === 'logout')
                                    <span class="badge bg-info">{{ strtoupper($log->action) }}</span>
                                @elseif($log->action === 'create')
                                    <span class="badge bg-success">{{ strtoupper($log->action) }}</span>
                                @elseif($log->action === 'update')
                                    <span class="badge bg-warning">{{ strtoupper($log->action) }}</span>
                                @elseif($log->action === 'delete')
                                    <span class="badge bg-danger">{{ strtoupper($log->action) }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ strtoupper($log->action) }}</span>
                                @endif
                            </td>
                            <td>{{ $log->table_name ?? '-' }}</td>
                            <td>{{ Str::limit($log->description, 50) }}</td>
                            <td>{{ $log->ip_address }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada log aktivitas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
