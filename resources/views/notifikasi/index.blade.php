<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f3f6f4;
            font-family: "Poppins", sans-serif;
        }

        .page-title {
            font-weight: 600;
            color: #2c3e50;
        }

        .notif-wrapper {
            max-width: 700px;
            margin: auto;
        }

        .notif-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 16px 18px;
            margin-bottom: 15px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #41A67E;
            transition: 0.3s;
        }

        .notif-card:hover {
            background: #f9fdfb;
        }

        .notif-unread {
            border-left: 4px solid #1e9e50;
            background: #eaffea;
        }

        .notif-unread-indicator {
            width: 10px;
            height: 10px;
            background: #1e9e50;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }

        .notif-time {
            font-size: 12px;
            color: #7f8c8d;
        }

        .back-btn {
            font-size: 15px;
            text-decoration: none;
            color: #41A67E;
            font-weight: 500;
        }

        .back-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container mt-4 notif-wrapper">

        {{-- Back Button --}}
        <a href="javascript:history.back()" class="back-btn">
            ← Kembali
        </a>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <h3 class="page-title">Notifikasi</h3>

            <div>
                {{-- Tandai Dibaca Semua --}}
                <form action="{{ route('notifikasi.readAll') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-primary">Tandai Dibaca</button>
                </form>

                {{-- Hapus Semua --}}
                <form action="{{ route('notifikasi.deleteAll') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                </form>
            </div>
        </div>

        <hr>

        {{-- List Notifikasi --}}
        @foreach ($notifikasi as $n)
            <div class="notif-card {{ !$n->is_read ? 'notif-unread' : '' }}">

                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex align-items-center">
                        @if (!$n->is_read)
                            <span class="notif-unread-indicator"></span>
                        @endif
                        <strong>{{ $n->judul }}</strong>
                    </div>

                    <small class="notif-time">{{ $n->created_at->diffForHumans() }}</small>
                </div>

                <p class="mt-2 mb-2">{{ $n->pesan }}</p>

                <div class="d-flex justify-content-end">

                    {{-- Tombol Hapus --}}
                    <form action="{{ route('notifikasi.delete', $n->id) }}" method="POST" class="ms-2">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                </div>
            </div>
        @endforeach

        @if ($notifikasi->count() == 0)
            <div class="alert alert-secondary text-center mt-4">
                Tidak ada notifikasi.
            </div>
        @endif

    </div>

</body>

</html>
