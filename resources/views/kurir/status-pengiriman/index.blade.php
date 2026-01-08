@extends('layouts.kurir.kurir')

@section('page-title', 'Status Pengiriman')

@section('content')
    <div class="card">

        <div class="card-header">
            <strong>Pengiriman Aktif</strong>
        </div>

        <div class="card-body p-0">

            @if ($pengiriman->isEmpty())
                <div class="p-4 text-center text-muted">
                    Tidak ada pengiriman aktif
                </div>
            @else
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Pesanan</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th width="200">Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengiriman as $p)
                            <tr>
                                <td>{{ $p->id_pesanan }}</td>
                                <td>{{ $p->alamat_tujuan }}</td>
                                <td>
                                    <span
                                        class="badge 
                                @if ($p->status_pengiriman == 'diproses') bg-warning
                                @elseif($p->status_pengiriman == 'dikirim') bg-primary
                                @else bg-success @endif
                            ">
                                        {{ strtoupper($p->status_pengiriman) }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST"
                                        action="{{ route('kurir.pengiriman.status.update', $p->id_pengiriman) }}">
                                        @csrf
                                        @method('PUT')

                                        <select name="status_pengiriman" class="form-select form-select-sm"
                                            onchange="this.form.submit()">
                                            <option value="diproses" @selected($p->status_pengiriman == 'diproses')>
                                                Diproses
                                            </option>
                                            <option value="dikirim" @selected($p->status_pengiriman == 'dikirim')>
                                                Dikirim
                                            </option>
                                            <option value="selesai">
                                                Selesai
                                            </option>
                                        </select>
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
