@extends('layouts.kurir.kurir')

@section('title', 'Inbox Kurir')
@section('page-title', 'Pesan Masuk')

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
</style>

<div class="card">
    <div class="card-header">
        <strong>Daftar Pesanan Masuk</strong>
    </div>

    <div class="card-body p-0">
        @forelse($pesanan as $row)
            <a href="{{ route('kurir.inbox.detail', $row->id) }}" class="d-block text-dark text-decoration-none">
                <div class="msg-item {{ $row->status == 'baru' ? 'bg-light' : '' }}">
                    
                    <div class="msg-title">
                        Pesanan #{{ $row->id }} - {{ $row->nama_pembeli }}
                    </div>

                    <div class="msg-meta">
                        {{ Str::limit($row->pesan ?? 'Tidak ada catatan', 50) }}
                        <br>
                        Status: 
                        <span class="badge 
                            @if($row->status == 'baru') bg-danger
                            @elseif($row->status == 'dibaca') bg-warning
                            @else bg-success @endif">
                            {{ ucfirst($row->status) }}
                        </span> 
                        | {{ $row->created_at->format('d M Y H:i') }}
                    </div>

                </div>
            </a>
        @empty
            <div class="p-4 text-center text-muted">Tidak ada pesan masuk.</div>
        @endforelse
    </div>
</div>

@endsection
