@extends('layouts.penjual.penjual')

@section('page-title', 'Riwayat Pesanan')

@section('content')

    <div class="card">
        <div class="card-header">
            <strong>Daftar Riwayat Pesanan</strong>
        </div>

        <div class="card-body p-0">

            @forelse($pesanan as $row)
                <div class="order-item p-3 border-bottom">

                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>#{{ $row->id_pesanan }}</strong><br>
                            Pembeli: {{ $row->user->nama ?? $row->user->name }}
                            <div class="text-muted small mt-1">
                                <i class="bi bi-clock"></i> {{ $row->updated_at->format('d M Y, H:i') }}
                            </div>
                        </div>

                        <div class="text-end">
                            @if($row->status_pesanan == 'Pesanan Selesai')
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Selesai</span>
                            @elseif($row->status_pesanan == 'Pesanan ditolak')
                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Ditolak</span>
                            @else
                                <span class="badge bg-secondary">{{ $row->status_pesanan }}</span>
                            @endif
                            
                            @if($row->pengiriman && $row->pengiriman->kurir && $row->pengiriman->kurir->user)
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-truck"></i> Kurir: {{ $row->pengiriman->kurir->user->nama ?? $row->pengiriman->kurir->user->name }}
                            </small>
                            @endif
                        </div>
                    </div>

                    <div class="mt-2">
                        <small class="fw-bold text-success" style="font-size: 1rem;">
                            Total: Rp {{ number_format($row->total_harga, 0, ',', '.') }}
                        </small>
                    </div>

                </div>
            @empty
                <div class="text-center p-5 text-muted">
                    <i class="bi bi-clipboard-x" style="font-size: 3rem; opacity: 0.5;"></i>
                    <h5 class="mt-3">Belum ada riwayat pesanan.</h5>
                    <p>Pesanan yang telah selesai atau ditolak akan muncul di sini.</p>
                </div>
            @endforelse

        </div>
    </div>

@endsection
