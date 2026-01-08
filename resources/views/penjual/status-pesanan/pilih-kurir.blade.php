@extends('layouts.penjual.penjual')

@section('page-title', 'Pilih Kurir')

@section('content')
<div class="card">

    <div class="card-header">
        <strong>Pilih Kurir – Pesanan {{ $pesanan->id_pesanan }}</strong>
    </div>

    {{-- FILTER --}}
    <div class="card-body border-bottom">
        <form method="GET" class="row g-2">

            <div class="col-md-5">
                <input type="text"
                       name="q"
                       value="{{ request('q') }}"
                       class="form-control"
                       placeholder="Cari nama kurir...">
            </div>

            <div class="col-md-4">
                <select name="provinsi" class="form-select">
                    <option value="">Semua Provinsi</option>
                    @foreach($provinsi as $p)
                        <option value="{{ $p->id_provinsi }}"
                            {{ request('provinsi') == $p->id_provinsi ? 'selected' : '' }}>
                            {{ $p->nama_provinsi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <button class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>

        </form>
    </div>

    {{-- TABLE --}}
    <div class="card-body p-0">

        @if($kurir->isEmpty())
            <div class="p-4 text-center text-muted">
                Tidak ada kurir sesuai filter
            </div>
        @else
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama Kurir</th>
                        <th>Wilayah</th>
                        <th>Kendaraan</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($kurir as $k)
                    <tr>
                        <td>
                            <strong>{{ $k->user->nama ?? '-' }}</strong>
                        </td>

                        <td>
                            {{ $k->provinsi->nama_provinsi ?? '-' }}
                        </td>

                        <td>{{ ucfirst($k->tipe_kendaraan) }}</td>

                        <td>
                            <span class="badge bg-success">
                                Aktif
                            </span>
                        </td>

                        <td>
                            <form method="POST"
                                  action="{{ route('penjual.pesanan.kurir.simpan', $pesanan->id_pesanan) }}">
                                @csrf
                                <input type="hidden" name="id_kurir" value="{{ $k->id_kurir }}">
                                <button class="btn btn-sm btn-success">
                                    Pilih
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

    </div>
</div>
@endsection
