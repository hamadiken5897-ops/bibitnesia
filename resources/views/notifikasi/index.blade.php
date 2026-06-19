@extends($layout)

@section('content')
<style>
    .notif-container {
        max-width: 800px;
        margin: 0 auto;
    }
    .notif-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        border-left: 4px solid #dcdde1;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .notif-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
    }
    .notif-unread {
        border-left: 4px solid #27ae60;
        background: #f8fdf9;
    }
    .notif-unread-indicator {
        width: 10px;
        height: 10px;
        background: #27ae60;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        box-shadow: 0 0 0 4px rgba(39, 174, 96, 0.1);
    }
    .notif-title {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0;
        font-size: 1.05rem;
    }
    .notif-time {
        font-size: 0.8rem;
        color: #95a5a6;
        font-weight: 500;
    }
    .notif-body {
        color: #576574;
        margin-top: 8px;
        margin-bottom: 0;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    .page-header-notif {
        border-bottom: 2px dashed #ecf0f1;
        padding-bottom: 15px;
        margin-bottom: 25px;
    }
    .btn-action-notif {
        border-radius: 50px;
        padding: 5px 15px;
        font-weight: 600;
        font-size: 0.85rem;
    }
</style>

<div class="container py-4 notif-container">
    <div class="mb-3">
        <a href="{{ $backUrl }}" class="btn btn-sm btn-outline-secondary btn-action-notif">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-center page-header-notif">
        <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-bell me-2"></i>Notifikasi</h4>

        @if($notifikasi->count() > 0)
        <div>
            <form action="{{ route('notifikasi.readAll') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-success btn-action-notif me-2"><i class="bi bi-check2-all"></i> Tandai Dibaca Semua</button>
            </form>

            <form action="{{ route('notifikasi.deleteAll') }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus semua notifikasi?');">
                @csrf
                <button class="btn btn-outline-danger btn-action-notif"><i class="bi bi-trash"></i> Bersihkan</button>
            </form>
        </div>
        @endif
    </div>

    <div class="notif-list">
        @forelse ($notifikasi as $n)
            <div class="notif-card {{ !$n->is_read ? 'notif-unread' : '' }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex align-items-center">
                        @if (!$n->is_read)
                            <span class="notif-unread-indicator"></span>
                        @endif
                        <h6 class="notif-title">{{ $n->judul }}</h6>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="notif-time me-3"><i class="bi bi-clock me-1"></i>{{ $n->created_at->diffForHumans() }}</span>
                        <form action="{{ route('notifikasi.delete', $n->id) }}" method="POST" class="m-0">
                            @csrf
                            <button class="btn btn-sm text-danger p-0" title="Hapus"><i class="bi bi-x-circle fs-5"></i></button>
                        </form>
                    </div>
                </div>
                <p class="notif-body">{{ $n->pesan }}</p>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-bell-slash text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                <h5 class="text-muted mt-3 fw-bold">Belum ada notifikasi</h5>
                <p class="text-muted">Semua pembaruan terkait pesanan Anda akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
