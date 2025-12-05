@extends('layouts.kurir.kurir')

@section('title', 'Detail Pesan - Kurir')
@section('page-title', 'Detail Pesanan')

@section('content')

<style>
    .message-card {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .msg-header {
        border-bottom: 1px solid #eee;
        margin-bottom: 15px;
        padding-bottom: 10px;
    }
    .msg-title {
        font-size: 19px;
        font-weight: 600;
    }
    .msg-meta {
        font-size: 13px;
        color: #777;
    }
</style>

<div class="row">
    <div class="col-12 col-lg-8">

        <div class="message-card">

            <div class="msg-header">
                <div class="msg-title">
                    Pesanan #{{ $pesanan->id }} - {{ $pesanan->nama_pembeli }}
                </div>

                <div class="msg-meta">
                    Tanggal: {{ $pesanan->created_at->format('d M Y H:i') }} |
                    Status: <b>{{ ucfirst($pesanan->status) }}</b>
                </div>
            </div>

            <p><strong>Alamat:</strong> {{ $pesanan->alamat }}</p>
            <p><strong>Catatan Pembeli:</strong> {{ $pesanan->pesan ?? '-' }}</p>

            <hr>

            <h5>Detail Barang</h5>

            <table class="table table-bordered mt-2">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesanan->detail as $item)
                    <tr>
                        <td>{{ $item->nama_produk }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($item->qty * $item->harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h4 class="text-end mt-3">
                Total: <strong>Rp{{ number_format($pesanan->total, 0, ',', '.') }}</strong>
            </h4>

            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('kurir.inbox') }}" class="btn btn-secondary">Kembali</a>

                @if($pesanan->status != 'selesai')
                <form action="{{ route('kurir.inbox.selesai', $pesanan->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-success">Tandai Selesai</button>
                </form>
                @endif
            </div>

        </div>

    </div>
</div>

@endsection
