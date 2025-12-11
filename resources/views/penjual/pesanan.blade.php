@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h3 class="mb-4">Daftar Pesanan</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($pesanan->isEmpty())
        <div class="alert alert-info">
            Belum ada pesanan.
        </div>
    @else

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Kode Pesanan</th>
                    <th>Pembeli</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($pesanan as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->kode_pesanan }}</td>
                    <td>{{ $p->pembeli->nama ?? '-' }}</td>
                    <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>

                    <td>
                        @if($p->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($p->status == 'diproses')
                            <span class="badge bg-primary">Diproses</span>
                        @elseif($p->status == 'dikirim')
                            <span class="badge bg-info">Dikirim</span>
                        @elseif($p->status == 'selesai')
                            <span class="badge bg-success">Selesai</span>
                        @endif
                    </td>

                    <td>{{ $p->created_at->format('d-m-Y') }}</td>

                    <td>
                        <a href="{{ route('penjual.pesanan.detail', $p->id) }}" class="btn btn-sm btn-info">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    @endif

</div>
@endsection
