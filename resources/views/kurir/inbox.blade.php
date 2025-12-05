@extends('layouts.kurir.kurir')

@section('title', 'Inbox - BibitNesia Kurir')
@section('page-title', 'Inbox Pesan')

@section('content')

<style>
    .card {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-radius: 12px;
        transition: 0.3s ease;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .badge-unread {
        background: #e74c3c20;
        color: #e74c3c;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-read {
        background: #0da76020;
        color: #0da760;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .msg-user {
        font-weight: 600;
        font-size: 14px;
    }

    .msg-preview {
        font-size: 13px;
        color: #777;
    }
</style>

<section class="row">

    {{-- STATISTIK INBOX --}}
    <div class="col-12 mb-3">
        <div class="row">

            <div class="col-6 col-lg-4 col-md-6">
                <div class="card p-3">
                    <h6 class="text-muted">Total Pesan</h6>
                    <h5 class="fw-bold">38</h5>
                </div>
            </div>

            <div class="col-6 col-lg-4 col-md-6">
                <div class="card p-3">
                    <h6 class="text-muted">Belum Dibaca</h6>
                    <h5 class="fw-bold text-danger">6</h5>
                </div>
            </div>

            <div class="col-12 col-lg-4 col-md-12">
                <div class="card p-3">
                    <h6 class="text-muted">Pesan Hari Ini</h6>
                    <h5 class="fw-bold text-success">4</h5>
                </div>
            </div>

        </div>
    </div>

    {{-- DAFTAR INBOX --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="fw-semibold">Daftar Pesan Masuk</h5>
            </div>

            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pengirim</th>
                            <th>Subjek</th>
                            <th>Preview</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>1</td>
                            <td class="msg-user">Admin BibitNesia</td>
                            <td>Konfirmasi Pembayaran</td>
                            <td class="msg-preview">Mohon cek ulang pembayaran order...</td>
                            <td><span class="badge-unread">Unread</span></td>
                            <td>03 Jan 2025</td>
                            <td>
                                <a href="{{ route('kurir.inbox.detail', 1) }}" class="btn btn-success btn-sm">
                                    Buka
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td class="msg-user">Rina Putri</td>
                            <td>Alamat Pengiriman</td>
                            <td class="msg-preview">Kak alamat saya diubah ya...</td>
                            <td><span class="badge-read">Read</span></td>
                            <td>03 Jan 2025</td>
                            <td>
                                <a href="{{ route('kurir.inbox.detail', 2) }}" class="btn btn-outline-success btn-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td>3</td>
                            <td class="msg-user">Admin</td>
                            <td>Pengiriman Gagal</td>
                            <td class="msg-preview">Mohon follow up pengiriman ID...</td>
                            <td><span class="badge-unread">Unread</span></td>
                            <td>02 Jan 2025</td>
                            <td>
                                <a href="{{ route('kurir.inbox.detail', 3) }}" class="btn btn-success btn-sm">
                                    Buka
                                </a>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>

@endsection
