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
                                            onchange="confirmSelesai(this, '{{ $p->status_pengiriman }}')">
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

@section('scripts')
<script>
    function confirmSelesai(selectElem, oldStatus) {
        if (selectElem.value === 'selesai') {
            Swal.fire({
                title: 'Konfirmasi Selesai',
                text: 'Yakin ingin menyelesaikan pengiriman ini? (Saldo pendapatan akan otomatis diteruskan ke penjual)',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Selesai!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    selectElem.form.submit();
                } else {
                    selectElem.value = oldStatus;
                }
            });
        } else {
            selectElem.form.submit();
        }
    }
</script>
@endsection
