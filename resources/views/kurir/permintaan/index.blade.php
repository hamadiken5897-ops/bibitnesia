@extends('layouts.kurir.kurir')

@section('title', 'Permintaan Penjemputan')
@section('page-title', 'Permintaan Penjemputan Paket')

@section('content')

    <div class="d-flex justify-content-center">
        <div style="max-width: 800px; width: 100%;">

            <div class="card">
                <div class="card-header">
                    <strong>Daftar Permintaan Penjemputan</strong>
                </div>

                <div class="card-body p-0">
                    @forelse ($pengiriman as $p)
                        <div class="msg-item">
                            <div class="msg-title">
                                Pesanan #{{ $p->id_pesanan }}
                            </div>

                            <div class="msg-meta">
                                <strong>Alamat Tujuan:</strong> {{ $p->alamat_tujuan }} <br>
                                <strong>Status:</strong>
                                <span class="badge bg-warning text-dark">
                                    Menunggu Penjemputan
                                </span>
                            </div>

                            <div class="mt-3">
                                <form method="POST" action="{{ route('kurir.permintaan.terima', $p->id_pengiriman) }}">
                                    @csrf
                                    <button class="btn btn-success btn-sm">
                                        Terima Tugas Penjemputan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-inbox"></i>
                            </div>
                            <h4>Belum Ada Permintaan</h4>
                            <p>
                                Saat ini belum ada penjual yang menugaskan penjemputan paket kepada Anda.
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
