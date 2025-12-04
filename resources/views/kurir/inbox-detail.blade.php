@extends('layouts.kurir.kurir')

@section('title', 'Detail Pesan - BibitNesia Kurir')
@section('page-title', 'Detail Pesan')

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
        font-size: 18px;
        font-weight: 600;
    }

    .msg-meta {
        font-size: 13px;
        color: #777;
    }

    .msg-body {
        font-size: 15px;
        line-height: 1.8;
        color: #333;
    }
</style>

<div class="row">
    <div class="col-12 col-lg-8">

        <div class="message-card">

            <div class="msg-header">
                <div class="msg-title">
                    {{ $message['subjek'] }}
                </div>
                <div class="msg-meta">
                    Pengirim: <b>{{ $message['pengirim'] }}</b> |
                    Tanggal: {{ $message['tanggal'] }} |
                    Status: <b>{{ $message['status'] }}</b>
                </div>
            </div>

            <div class="msg-body">
                <p>
                    {{ $message['pesan'] }}
                </p>
            </div>

            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('kurir.inbox') }}" class="btn btn-secondary">
                    Kembali
                </a>

                <button class="btn btn-success">
                    Tandai Dibaca
                </button>
            </div>

        </div>

    </div>
</div>

@endsection
