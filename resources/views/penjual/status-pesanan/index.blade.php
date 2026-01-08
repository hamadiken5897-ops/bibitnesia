@extends('layouts.penjual.penjual')

@section('page-title', 'Status Pesanan')

@section('content')

    <div class="card">
        <div class="card-header">
            <strong>Daftar Pesanan</strong>
        </div>

        <div class="card-body p-0">

            @forelse($pesanan as $row)
                <div class="order-item p-3 border-bottom">

                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>#{{ $row->kode_invoice }}</strong><br>
                            Pembeli: {{ $row->user->name }}
                        </div>

                        <div>
                            @if (!$row->pengiriman)
                                <span class="badge bg-warning">Belum Dikirim</span>
                            @else
                                <span class="badge bg-info">
                                    {{ ucfirst($row->pengiriman->status_pengiriman) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-2">
                        <small>
                            Total: Rp {{ number_format($row->total_harga, 0, ',', '.') }}
                        </small>
                    </div>

                    <div class="mt-3">
                       @if (!$row->pengiriman) 
                            <a href="{{ route('penjual.pesanan.kurir', $row->id_pesanan) }}" class="btn btn-sm btn-primary">
                                Cari Kurir
                            </a>
                        @else  
                         {{--    <a href="{{ route('penjual.pengiriman.show', $row->pengiriman->id_pengiriman) }}"
                                class="btn btn-sm btn-outline-secondary">
                                Lihat Pengiriman
                            </a> --}} 
                        @endif  
                    </div>

                </div>
            @empty
                <div class="text-center p-5 text-muted">
                    Belum ada pesanan yang diproses.
                </div>
            @endforelse

        </div>
    </div>

@endsection
