@extends('layouts.penjual.penjual')

@section('content')
<div class="container mt-4">
    <h3>Tambah Produk</h3>

    <form>
        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" class="form-control">
        </div>

        <button class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
