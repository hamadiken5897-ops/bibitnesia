@extends('layouts.penjual.penjual')

@section('title', 'Daftar Pengiriman')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Manajemen Pengiriman</h3>
    </div>
</div>

<div class="page-content">
    <!-- Notifikasi Sukses/Error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible show fade">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header pb-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <h5 class="card-title mb-0">Daftar Pengiriman Toko Anda</h5>
            <form action="{{ route('penjual.pengiriman.index') }}" method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari Resi/Pembeli..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Cari</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive mt-3">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Info Pesanan</th>
                            <th>No. Resi</th>
                            <th>Pembeli</th>
                            <th>Kurir</th>
                            <th>Status</th>
                            <th>Tgl Dikirim</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengiriman as $index => $item)
                            <tr>
                                <td>{{ $pengiriman->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $item->pesanan->id_pesanan }}</strong>
                                </td>
                                <td>
                                    @if($item->no_resi)
                                        <span class="badge bg-light-primary text-primary">{{ $item->no_resi }}</span>
                                    @else
                                        <span class="text-muted"><i class="bi bi-clock"></i> Menunggu Resi</span>
                                    @endif
                                </td>
                                <td>{{ $item->pesanan->user->nama ?? 'N/A' }}</td>
                                <td>{{ $item->kurir->user->nama ?? 'Belum ada kurir' }}</td>
                                <td>
                                    @if($item->status_pengiriman == 'diproses')
                                        <span class="badge bg-warning">Diproses</span>
                                    @elseif($item->status_pengiriman == 'dikirim')
                                        <span class="badge bg-info">Dikirim</span>
                                    @elseif($item->status_pengiriman == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($item->status_pengiriman) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->tanggal_pengiriman)
                                        {{ \Carbon\Carbon::parse($item->tanggal_pengiriman)->format('d M Y, H:i') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('penjual.pengiriman.show', $item->id_pengiriman) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data pengiriman</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3 d-flex justify-content-end">
                {{ $pengiriman->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
