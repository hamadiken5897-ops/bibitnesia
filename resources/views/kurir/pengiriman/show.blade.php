@extends('layouts.kurir.kurir')

@section('title', 'Detail Pengiriman')

@section('content')
<div class="card">
    <div class="card-header">
        <strong>Detail Pengiriman</strong>
    </div>

    <div class="card-body">
        <p><strong>ID Pesanan:</strong> {{ $pengiriman->id_pesanan }}</p>
        <p><strong>Nama Pembeli:</strong> {{ $pengiriman->pesanan->user->name }}</p>
        <p><strong>Alamat Tujuan:</strong> {{ $pengiriman->alamat_tujuan }}</p>
        <p><strong>Status:</strong> {{ ucfirst($pengiriman->status_pengiriman) }}</p>

        <hr>

        <h5>Detail Barang</h5>
        <ul>
            @foreach($pengiriman->pesanan->detailPesanan as $item)
                <li>
                    {{ $item->produk->nama_produk }}
                    ({{ $item->jumlah }} ×
                    Rp {{ number_format($item->harga_satuan, 0, ',', '.') }})
                </li>
            @endforeach
        </ul>

        <hr>

        @if($pengiriman->status_pengiriman === 'dikemas')
            <form method="POST"
                action="{{ route('kurir.pengiriman.accept', $pengiriman->id_pengiriman) }}">
                @csrf
                <button class="btn btn-success">
                    Terima Pengiriman
                </button>
            </form>
        @elseif($pengiriman->status_pengiriman === 'dikirim')
            <form method="POST"
                action="{{ route('kurir.pengiriman.selesai', $pengiriman->id_pengiriman) }}">
                @csrf
                <button class="btn btn-primary">
                    Selesaikan Pengiriman
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
