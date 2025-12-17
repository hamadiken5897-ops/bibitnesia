@extends('layouts.penjual.penjual')

@section('content')
    <div class="container mt-4">

        <h3 class="mb-4">Pesanan Masuk</h3>

        @if ($pesanan->isEmpty())
            <div class="alert alert-info">
                Belum ada pesanan masuk.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>ID Pesanan</th>
                            <th>Kode Invoice</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pesanan as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $p->id_pesanan }}</td>
                                <td>{{ $p->kode_invoice }}</td>
                                <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-warning">
                                        {{ $p->status_pesanan }}
                                    </span>
                                </td>
                                <td>{{ $p->created_at->format('d-m-Y') }}</td>
                                <td>
                                    {{-- ACCEPT --}}
                                    <form action="{{ route('penjual.pesanan.accept', $p->id_pesanan) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button class="btn btn-success btn-sm">Accept</button>
                                    </form>

                                    {{-- REJECT --}}
                                    <form method="POST" action="{{ route('penjual.pesanan.reject', $p->id_pesanan) }}">
                                        @csrf
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @foreach ($pesanan as $p)
                    <div class="modal fade" id="rejectModal{{ $p->id_pesanan }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('penjual.pesanan.reject', $p->id_pesanan) }}">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tolak Pesanan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <label>Alasan Penolakan</label>
                                        <textarea name="alasan" class="form-control" required></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-danger">Tolak Pesanan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach

            </div>
        @endif

    </div>
@endsection
