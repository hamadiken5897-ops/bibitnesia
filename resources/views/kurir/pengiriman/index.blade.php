@extends('layouts.kurir.kurir')

@section('title', 'Inbox Pengiriman')
@section('page-title', 'Pengiriman Masuk')

@section('content')

    <div class="d-flex justify-content-center">
        <div style="max-width: 800px; width: 100%;">

            <div class="card">
                <div class="card-header">
                    <strong>Daftar Pengiriman</strong>
                </div>

                <div class="card-body p-0">
                    @forelse ($pengiriman as $p)
                        <div class="msg-item">
                            <div class="msg-title">
                                Pesanan #{{ $p->id_pesanan }}
                            </div>

                            <div class="msg-meta">
                                <strong>Alamat:</strong> {{ $p->alamat_tujuan }} <br>
                                <strong>Status:</strong>
                                <span
                                    class="badge
                                @if ($p->status_pengiriman === 'dikemas') bg-warning
                                @elseif($p->status_pengiriman === 'dikirim') bg-primary
                                @else bg-success @endif">
                                    {{ ucfirst($p->status_pengiriman) }}
                                </span>
                            </div>

                            <div class="mt-3">
                                @if ($p->status_pengiriman === 'dikemas')
                                    <form method="POST" action="{{ route('kurir.pengiriman.accept', $p->id_pengiriman) }}">
                                        @csrf
                                        <button class="btn btn-success btn-sm">
                                            Terima Pengiriman
                                        </button>
                                    </form>
                                @elseif($p->status_pengiriman === 'dikirim')
                                    <form method="POST"
                                        action="{{ route('kurir.pengiriman.selesai', $p->id_pengiriman) }}">
                                        @csrf
                                        <button class="btn btn-primary btn-sm">
                                            Selesaikan Pengiriman
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <h4>Belum Ada Pengiriman</h4>
                            <p>
                                Saat ini belum ada pesanan yang dikirimkan ke Anda.<br>
                                Pengiriman akan muncul setelah penjual memilih kurir.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <style>
        .msg-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .msg-title {
            font-weight: bold;
        }

        .msg-meta {
            font-size: 13px;
            color: #777;
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: #6c757d;
        }

        .empty-icon {
            font-size: 64px;
            color: #cfd8dc;
            margin-bottom: 20px;
        }
    </style>

@endsection
