@extends('layouts.kurir.kurir')

@section('title', 'Inbox Pengiriman')
@section('page-title', 'Pengiriman Masuk')

@section('content')

    <style>
        .msg-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: .2s;
        }

        .msg-item:hover {
            background: #f8f9fa;
        }

        .msg-title {
            font-size: 16px;
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

        .empty-state h4 {
            font-weight: 600;
            margin-bottom: 10px;
            color: #455a64;
        }

        .empty-state p {
            font-size: 14px;
            margin-bottom: 15px;
        }

        .page-content {
            min-height: 60vh;
        }

        .card {
            margin-top: 30px;
        }
    </style>

    <div class="card">
        <div class="card-header">
            <strong>Daftar Pengiriman</strong>
        </div>

        <div class="card-body p-0">
            @forelse($pengiriman as $row)
                <a href="{{ route('kurir.pengiriman.show', $row->id_pengiriman) }}"
                    class="d-block text-dark text-decoration-none">

                    <div class="msg-item">

                        <div class="msg-title">
                            Pesanan #{{ $row->id_pesanan }}
                            — {{ $row->pesanan->user->name }}
                        </div>

                        <div class="msg-meta">
                            <strong>Alamat:</strong>
                            {{ $row->alamat_tujuan }}
                            <br>

                            <strong>Status:</strong>
                            <span
                                class="badge
                            @if ($row->status_pengiriman === 'dikemas') bg-warning
                            @elseif($row->status_pengiriman === 'dikirim') bg-primary
                            @else bg-success @endif">
                                {{ ucfirst($row->status_pengiriman) }}
                            </span>

                            | {{ $row->created_at->format('d M Y H:i') }}
                        </div>

                    </div>
                </a>
                <div class="page-content" id="ajax-content">
                    <div class="d-flex justify-content-center">
                        <div style="max-width: 700px; width: 100%;">

                            <div class="card">
                                <div class="card-header">
                                    <strong>Daftar Pengiriman</strong>
                                </div>

                                <div class="card-body p-0">
                                    @forelse($pengiriman as $row)
                                        {{-- isi pengiriman --}}
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
                                            <span class="badge bg-light text-secondary">
                                                Menunggu penugasan
                                            </span>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection
