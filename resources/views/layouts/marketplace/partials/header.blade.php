<div class="header">
    <div class="header-title">
        <h1>Marketplace Bibitnesia</h1>
        <p>Temukan tanaman & bibit berkualitas dari penjual terpercaya</p>
    </div>

    <div class="header-actions">
        @auth
            @php
                $notifs = \App\Models\NotifikasiUser::where('id_user', auth()->user()->id_user)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();

                // Tidak manipulasi judul → semua type & icon langsung dari database
                $notif_mapped = $notifs->map(function ($n) {
                    return [
                        'id' => $n->id_notif,
                        'judul' => $n->judul,
                        'pesan' => $n->pesan,
                        'url' => $n->redirect_url,
                        'time' => $n->created_at->diffForHumans(),
                        'type' => $n->type ?? 'normal',
                        'icon' => $n->icon ?? '🔔',
                    ];
                });
            @endphp

            <script>
                window.notifs = @json($notif_mapped);
            </script>

            <div class="nav-item ms-lg-3 position-relative">
                <a href="{{ route('notifikasi.index', ['from' => request()->path()]) }}">
                    <i class="bi bi-bell-fill fs-4" style="color:#41A67E;"></i>
                    @if ($notifCount > 0)
                        <span class="badge bg-danger">{{ $notifCount }}</span>
                    @endif
                </a>
            </div>

        @endauth
        @auth
            <button class="profile-btn">
                {{ auth()->user()->name }}
            </button>
        @else
            <a href="{{ route('login') }}" class="profile-btn">
                Login
            </a>
        @endauth
    </div>
</div>
