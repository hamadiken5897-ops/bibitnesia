@extends('marketplace.layouts')

@section('content')

<div class="container mt-4">

    <h3 class="fw-bold mb-4">Favorit Saya</h3>

    @if ($favorit->count() == 0)
        <div class="alert alert-info">Belum ada produk favorit.</div>
    @else

        <div class="row">
            @foreach ($favorit as $item)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">

                        <img src="{{ asset('storage/' . $item->produk->gambar) }}"
                             class="card-img-top"
                             style="height: 200px; object-fit: cover;">

                        <div class="card-body">

                            <h5 class="fw-bold">{{ $item->produk->nama }}</h5>

                            <p class="text-success fw-semibold">
                                Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                            </p>

                            <div class="d-flex justify-content-between">

                                {{-- Tambahkan ke keranjang --}}
                                <form action="{{ route('keranjang.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="produk_id" value="{{ $item->produk->id }}">
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-shopping-cart"></i> Keranjang
                                    </button>
                                </form>

                                {{-- Hapus favorit --}}
                                <form action="{{ route('favorit.delete', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @endif

</div>

@endsection
