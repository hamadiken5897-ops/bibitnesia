@extends('marketplace.layouts')

@section('content')
<div class="container py-4">

    <h3 class="mb-4 fw-bold">Keranjang Saya</h3>

    @foreach($keranjang as $item)
        <div class="card shadow-sm border-0 mb-3 p-3">
            <div class="row align-items-center">

                {{-- Foto Produk --}}
                <div class="col-3">
                    <img src="{{ asset('storage/' . $item->produk->foto) }}"
                         class="img-fluid rounded"
                         style="object-fit: cover; width: 100%; height: 90px;">
                </div>

                {{-- Nama Produk --}}
                <div class="col-6">
                    <h6 class="fw-bold">{{ $item->produk->nama }}</h6>
                    <small class="text-muted">{{ $item->produk->volume ?? '' }}</small>

                    <div class="mt-2 fw-bold" style="font-size: 14px;">
                        Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                    </div>
                </div>

                {{-- Qty + Hapus --}}
                <div class="col-3 text-end">

                    {{-- Form Update Qty --}}
                    <form action="{{ route('keranjang.update', $item->id) }}" method="POST" class="d-inline">
                        @csrf

                        <div class="d-flex justify-content-end align-items-center">

                            <button class="btn btn-sm btn-light border"
                                onclick="this.parentNode.querySelector('input[name=qty]').stepDown();">
                                -
                            </button>

                            <input type="number" name="qty" value="{{ $item->qty }}"
                                min="1"
                                class="form-control form-control-sm mx-1 text-center"
                                style="width: 50px;">

                            <button class="btn btn-sm btn-light border"
                                onclick="this.parentNode.querySelector('input[name=qty]').stepUp();">
                                +
                            </button>

                        </div>

                        <button class="btn btn-sm btn-primary mt-2 w-100">
                            Update
                        </button>
                    </form>

                    {{-- Hapus --}}
                    <form action="{{ route('keranjang.delete', $item->id) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger w-100">
                            Hapus
                        </button>
                    </form>

                </div>
            </div>
        </div>
    @endforeach


    {{-- === Ringkasan Harga === --}}
    <div class="card shadow-sm border-0 p-3 mt-4">
        <h5 class="fw-bold">Ringkasan Belanja</h5>

        <div class="d-flex justify-content-between mt-2">
            <span>Subtotal</span>
            <span class="fw-bold">Rp
                {{ number_format($keranjang->sum(fn($i) => $i->qty * $i->produk->harga), 0, ',', '.') }}
            </span>
        </div>

        <div class="d-flex justify-content-between">
            <span>Total Item</span>
            <span>{{ $keranjang->sum('qty') }} item</span>
        </div>

        <hr>

        <h5 class="fw-bold">
            TOTAL:
            Rp {{ number_format($keranjang->sum(fn($i) => $i->qty * $i->produk->harga), 0, ',', '.') }}
        </h5>

        <button class="btn btn-warning w-100 py-2 fw-bold mt-3">
            Checkout
        </button>
    </div>

</div>
@endsection
