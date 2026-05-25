@extends('layouts.admin.admin')

@section('title', 'Manajemen Pembayaran')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Manajemen Pembayaran</h3>
    </div>
</div>

<div class="page-content">

    {{-- Alert --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card Table --}}
    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID Pembayaran</th>
                            <th>ID Pesanan</th>
                            <th>Metode</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th>Tanggal Bayar</th>
                            <th>Dibuat</th>
                            <th>Diupdate</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($pembayarans as $index => $p)
                            <tr>
                                <td>{{ $pembayarans->firstItem() + $index }}</td>
                                <td>{{ $p->id_pembayaran }}</td>
                                <td>{{ $p->id_pesanan }}</td>
                                <td>{{ ucfirst($p->metode_pembayaran) }}</td>
                                <td>Rp {{ number_format($p->total_bayar, 0, ',', '.') }}</td>

                                <td>
                                    @if ($p->status_validasi === 'paid' || strtolower($p->status_validasi ?? '') === 'valid' || $p->status_validasi === 'sudah_bayar')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif ($p->status_validasi === 'pending' || $p->status_validasi === 'belum_bayar')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                </td>

                                <td>{{ $p->tanggal_pembayaran ?? '-' }}</td>
                                <td>{{ $p->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ $p->updated_at->format('d-m-Y H:i') }}</td>

                                <td>
                                    <a href="{{ route('admin.pembayaran.show', $p->id_pembayaran) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">
                                    Belum ada data pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $pembayarans->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
