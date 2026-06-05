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

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Invoice Pesanan</th>
                            <th>Total Pemasukan</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $row)
                            <tr>
                                <td class="fw-bold">#{{ $row->id_pesanan }}</td>
                                <td class="text-success fw-bold">
                                    Rp {{ number_format($row->total_jumlah, 0, ',', '.') }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($row->tgl_masuk)->format('d M Y H:i') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $row->id_pesanan }}">
                                        <i class="bi bi-eye"></i> Lihat Detail
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Modal Detail -->
                            <div class="modal fade" id="modalDetail{{ $row->id_pesanan }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Pemasukan #{{ $row->id_pesanan }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="list-group list-group-flush">
                                                @if(isset($pesananDetails[$row->id_pesanan]))
                                                    @foreach($pesananDetails[$row->id_pesanan]->detailPesanan as $detail)
                                                        @if($detail->produk)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                            <div>
                                                                <h6 class="mb-0">{{ $detail->produk->nama_produk }}</h6>
                                                                <small class="text-muted">{{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</small>
                                                            </div>
                                                            <strong class="text-success">Rp {{ number_format($detail->jumlah * $detail->harga_satuan, 0, ',', '.') }}</strong>
                                                        </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="modal-footer d-flex justify-content-between">
                                            <span class="fw-bold">Total:</span>
                                            <span class="fw-bold text-success">Rp {{ number_format($row->total_jumlah, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Belum ada pemasukan yang dicatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $laporan->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
