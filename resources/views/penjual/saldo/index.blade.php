@extends('layouts.penjual.penjual')

@section('content')
<div class="container">

    <h4 class="mb-4 fw-bold">Laporan Pemasukan</h4>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <small>Total Pemasukan</small>
                    <h3 class="fw-bold text-success">
                        Rp {{ number_format($total_pemasukan, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <small>Pesanan Selesai</small>
                    <h3 class="fw-bold">{{ $total_pesanan }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="mb-3">Riwayat Pemasukan</h6>

            <table class="table">
                <thead>
                    <tr>
                        <th>Pesanan</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $row)
                        <tr>
                            <td>#{{ $row->id_pesanan }}</td>
                            <td class="text-success">
                                Rp {{ number_format($row->jumlah, 0, ',', '.') }}
                            </td>
                            <td>{{ $row->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Belum ada pemasukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
@endsection
