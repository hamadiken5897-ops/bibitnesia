@extends('layouts.kurir.kurir')

@section('page-title', 'Riwayat Pengiriman')

@section('content')
    <div class="card">

        <div class="card-header">
            <strong>Pengiriman Selesai</strong>
        </div>

        <div class="card-body p-0">

            @if ($pengiriman->isEmpty())
                <div class="p-4 text-center text-muted">
                    Belum ada riwayat pengiriman yang diselesaikan.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Pesanan</th>
                                <th>Alamat Tujuan</th>
                                <th>Status</th>
                                <th>Tanggal Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengiriman as $p)
                                <tr>
                                    <td>#{{ $p->id_pesanan }}</td>
                                    <td>{{ $p->alamat_tujuan }}</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ strtoupper($p->status_pengiriman) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($p->updated_at)->format('d M Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-3">
                    {{ $pengiriman->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection
