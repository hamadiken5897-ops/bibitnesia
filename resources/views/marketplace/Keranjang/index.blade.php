@extends('marketplace.index')

@section('content')
<div class="container">

    <h3>Keranjang Belanja</h3>
    <hr>

    @foreach ($items as $item)
    <div class="card mb-3 p-3">
        <div class="d-flex align-items-center">

            <img src="/storage/{{ $item->produk->gambar }}" width="120" class="rounded">

            <div class="ms-3">
                <h5>{{ $item->produk->nama }}</h5>
                <p>Rp {{ number_format($item->produk->harga) }}</p>

                <form action="{{ route('keranjang.update', $item->id) }}" method="POST">
                    @csrf
                    <input type="number" name="qty" value="{{ $item->qty }}" min="1" class="form-control w-25 d-inline">
                    <button class="btn btn-primary btn-sm">Update</button>
                </form>
            </div>

            <form action="{{ route('keranjang.delete', $item->id) }}" method="POST" class="ms-auto">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">Hapus</button>
            </form>

        </div>
    </div>
    @endforeach

</div>
@endsection
