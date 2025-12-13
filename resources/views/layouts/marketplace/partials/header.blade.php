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
            @php
                $authUser = auth()->user()->load('file');
            @endphp

            <div class="profile-dropdown">
                <button class="profile-btn" id="avatarToggle">
                    @if ($authUser->file)
                        <img src="{{ Storage::url($authUser->file->path) }}" class="profile-avatars" alt="Avatar">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($authUser->nama) }}&size=200&background=41A67E&color=fff"
                            class="profile-avatars" alt="Avatar">
                    @endif
                </button>

                <div class="dropdown-menu" id="avatarMenu">
                    <div class="dropdown-header">
                        <strong>{{ $authUser->nama }}</strong><br>
                        <small>{{ $authUser->email }}</small>
                    </div>

                    <a href="{{ route('profile.own') }}" class="dropdown-item">
                        <i class="bi bi-person"></i> Profil Saya
                    </a>

                    <a href="#" class="dropdown-item">
                        <i class="bi bi-gear"></i> Pengaturan Akun
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item text-danger w-100 text-start">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="profile-btn">
                Login
            </a>
        @endauth
    </div>
</div>
