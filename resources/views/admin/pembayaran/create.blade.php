@extends('layouts.admin')

@section('content')
<h3>Tambah Pembayaran</h3>

<form action="{{ route('admin.pembayaran.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>ID Pembayaran</label>
        <input type="text" name="id_pembayaran" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>ID Pesanan</label>
        <input type="text" name="id_pesanan" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Metode Pembayaran</label>
        <select name="metode_pembayaran" class="form-control" required>
            <option>Transfer Bank</option>
            <option>COD</option>
            <option>QRIS</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Total Bayar</label>
        <input type="number" name="total_bayar" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Tanggal Pembayaran</label>
        <input type="date" name="tanggal_pembayaran" class="form-control" required>
    </div>

    <button class="btn btn-primary">Simpan</button>
</form>
@endsection
