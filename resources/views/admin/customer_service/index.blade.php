@extends('layouts.admin.admin')

@section('title', 'Manajemen Komplain & Banned')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Manajemen Layanan Pengguna</h3>
    </div>
</div>

<div class="page-content">
    <!-- Tampilkan Notifikasi Sukses/Error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible show fade">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header pb-0">
            <!-- Nav Tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active font-weight-bold px-4 py-3" id="komplain-tab" data-bs-toggle="tab" data-bs-target="#komplain" type="button" role="tab" aria-controls="komplain" aria-selected="true" style="border-top-left-radius: 8px;">
                        <i class="bi bi-chat-left-text-fill text-primary me-2"></i> Laporan & Komplain Pengguna
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link font-weight-bold px-4 py-3" id="banned-tab" data-bs-toggle="tab" data-bs-target="#banned" type="button" role="tab" aria-controls="banned" aria-selected="false">
                        <i class="bi bi-slash-circle-fill text-danger me-2"></i> Daftar Pengguna Dibanned
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link font-weight-bold px-4 py-3" id="inbox-tab" data-bs-toggle="tab" data-bs-target="#inbox" type="button" role="tab" aria-controls="inbox" aria-selected="false" style="border-top-right-radius: 8px;">
                        <i class="bi bi-inboxes-fill text-success me-2"></i> Kotak Masuk CS
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content pt-3" id="myTabContent">
                
                <!-- TAB 1: LAPORAN & KOMPLAIN -->
                <div class="tab-pane fade show active" id="komplain" role="tabpanel" aria-labelledby="komplain-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover align-middle" id="table1">
                            <thead>
                                <tr>
                                    <th>ID Komplain</th>
                                    <th>Pelapor</th>
                                    <th>Kategori</th>
                                    <th>Objek Terkait</th>
                                    <th>Status</th>
                                    <th>Tanggal Masuk</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($komplains as $komplain)
                                    <tr>
                                        <td><span class="font-monospace text-primary">{{ $komplain->id_komplain }}</span></td>
                                        <td>
                                            <strong>{{ $komplain->user->nama ?? 'Tidak Diketahui' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ ucfirst($komplain->user->role ?? '-') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($komplain->kategori_laporan ?? 'Umum') }}</span>
                                        </td>
                                        <td>
                                            @if($komplain->kategori_laporan == 'produk' && $komplain->produk)
                                                Produk: <a href="{{ route('marketplace.show', $komplain->produk->id_produk) }}" target="_blank" class="text-primary">{{ Str::limit($komplain->produk->nama_produk, 20) }}</a>
                                            @elseif($komplain->kategori_laporan == 'ulasan' && $komplain->ulasan)
                                                Ulasan oleh: {{ $komplain->ulasan->user->nama ?? 'User' }}
                                            @elseif($komplain->kategori_laporan == 'pengguna' && $komplain->terlapor)
                                                Pengguna: <a href="{{ route('profile.show', $komplain->terlapor->id_user) }}" target="_blank" class="text-primary">{{ $komplain->terlapor->nama }}</a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($komplain->status === 'MENUNGGU')
                                                <span class="badge bg-warning text-dark">Menunggu</span>
                                            @elseif($komplain->status === 'DIPROSES')
                                                <span class="badge bg-info">Diproses</span>
                                            @elseif($komplain->status === 'SELESAI')
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-secondary">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>{{ $komplain->created_at ? $komplain->created_at->format('d M Y H:i') : '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.customer_service.show', $komplain->id_komplain) }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-eye-fill"></i> Review
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Tidak ada laporan komplain dari pengguna.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 2: PENGGUNA DIBANNED -->
                <div class="tab-pane fade" id="banned" role="tabpanel" aria-labelledby="banned-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover align-middle" id="table2">
                            <thead>
                                <tr>
                                    <th>ID Banned</th>
                                    <th>Pengguna</th>
                                    <th>Tipe Banned</th>
                                    <th>Waktu Banned</th>
                                    <th>Waktu Berakhir</th>
                                    <th>Alasan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($banneds as $ban)
                                    <tr>
                                        <td><span class="font-monospace text-danger">{{ $ban->id_banned }}</span></td>
                                        <td>
                                            <strong>{{ $ban->user->nama ?? 'Tidak Diketahui' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $ban->id_user }} ({{ ucfirst($ban->user->role ?? '-') }})</small>
                                        </td>
                                        <td>
                                            @if($ban->status === 'PERMANEN')
                                                <span class="badge bg-danger">Permanen</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Sementara</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($ban->tgl_banned)->format('d M Y H:i') }}</td>
                                        <td>
                                            @if($ban->status === 'PERMANEN')
                                                <span class="text-danger">Selamanya</span>
                                            @else
                                                {{ \Carbon\Carbon::parse($ban->tgl_berakhir)->format('d M Y H:i') }}
                                            @endif
                                        </td>
                                        <td>
                                            <span class="d-inline-block text-truncate" style="max-width: 250px;" title="{{ $ban->alasan }}">
                                                {{ $ban->alasan }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <!-- Form Unban -->
                                            <button type="button" class="btn btn-success btn-sm" onclick="confirmUnban('{{ $ban->id_user }}', '{{ $ban->user->nama ?? $ban->id_user }}')">
                                                <i class="bi bi-unlock-fill"></i> Buka Blokir (Unban)
                                            </button>
                                            <form id="unban-form-{{ $ban->id_user }}" action="{{ route('admin.banned.unban', $ban->id_user) }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Tidak ada pengguna yang sedang diblokir (banned).</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 3: INBOX CS -->
                <div class="tab-pane fade" id="inbox" role="tabpanel" aria-labelledby="inbox-tab">
                    <div class="row">
                        <div class="col-md-4 border-end" style="height: 500px; overflow-y: auto;" id="cs-inbox-list">
                            <div class="text-center mt-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 d-flex flex-column" style="height: 500px;">
                            <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center" id="cs-chat-header" style="display: none !important;">
                                <div class="d-flex align-items-center">
                                    <img src="" id="chat-header-img" class="rounded-circle me-3" width="40" height="40" style="object-fit:cover;">
                                    <h5 class="mb-0" id="chat-header-name">Nama Pengguna</h5>
                                </div>
                            </div>
                            
                            <div class="flex-grow-1 p-3 bg-white" style="overflow-y: auto;" id="cs-chat-body">
                                <div class="h-100 d-flex justify-content-center align-items-center text-muted">
                                    Pilih percakapan dari daftar di samping.
                                </div>
                            </div>
                            
                            <div class="p-3 border-top bg-light" id="cs-chat-footer" style="display: none !important;">
                                <form id="cs-chat-form" class="d-flex">
                                    <input type="hidden" id="active_chat_user" value="">
                                    <input type="text" id="cs_message_input" class="form-control me-2" placeholder="Ketik balasan Anda di sini..." autocomplete="off">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-send-fill"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleDatepicker(id) {
        const select = document.getElementById('banned_status_' + id);
        const dateContainer = document.getElementById('date_picker_container_' + id);
        if (select.value === 'SEMENTARA') {
            dateContainer.classList.remove('d-none');
            dateContainer.querySelector('input').setAttribute('required', 'true');
        } else {
            dateContainer.classList.add('d-none');
            dateContainer.querySelector('input').removeAttribute('required');
        }
    }

    function confirmUnban(userId, userName) {
        Swal.fire({
            title: 'Buka Blokir Pengguna?',
            text: "Apakah Anda yakin ingin memulihkan status akun " + userName + "?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Buka Blokir',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('unban-form-' + userId).submit();
            }
        });
    }

    // ==========================================
    // CUSTOMER SERVICE CHAT LOGIC
    // ==========================================
    let currentChatUserId = null;
    let chatPollingInterval = null;

    // Fetch Inbox List
    function fetchInbox() {
        fetch('{{ route('admin.cs_chat.inbox') }}')
            .then(res => res.json())
            .then(data => {
                const listContainer = document.getElementById('cs-inbox-list');
                if (data.length === 0) {
                    listContainer.innerHTML = '<div class="text-center mt-5 text-muted">Belum ada pesan masuk.</div>';
                    return;
                }
                
                let html = '';
                data.forEach(user => {
                    const isActive = currentChatUserId === user.id_user ? 'bg-light border-start border-4 border-success' : '';
                    const unreadBadge = user.unread_count > 0 ? `<span class="badge bg-danger rounded-pill">${user.unread_count}</span>` : '';
                    
                    html += `
                    <div class="p-3 border-bottom cursor-pointer user-inbox-item ${isActive}" 
                         style="cursor: pointer;"
                         onclick="openChat('${user.id_user}', '${user.nama}', '${user.profile_image}')">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="${user.profile_image}" class="rounded-circle me-3" width="45" height="45" style="object-fit:cover;">
                                <div>
                                    <h6 class="mb-0 fw-bold">${user.nama}</h6>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 150px;">${user.last_message}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">${user.last_time}</small>
                                ${unreadBadge}
                            </div>
                        </div>
                    </div>`;
                });
                listContainer.innerHTML = html;
            });
    }

    // Open Chat
    function openChat(id_user, nama, image) {
        currentChatUserId = id_user;
        document.getElementById('active_chat_user').value = id_user;
        
        // Update Header
        document.getElementById('cs-chat-header').style.setProperty('display', 'flex', 'important');
        document.getElementById('cs-chat-footer').style.setProperty('display', 'block', 'important');
        document.getElementById('chat-header-name').innerText = nama;
        document.getElementById('chat-header-img').src = image;

        // Fetch Messages
        fetchMessages(id_user);

        // Highlight selected inbox item
        fetchInbox(); // Re-render inbox to show active and remove unread badge if any
        
        // Setup polling
        if (chatPollingInterval) clearInterval(chatPollingInterval);
        chatPollingInterval = setInterval(() => {
            fetchMessages(id_user, false);
            fetchInbox();
        }, 3000);
    }

    // Fetch Messages
    function fetchMessages(id_user, scrollToBottom = true) {
        fetch(`/admin/customer-service-chat/${id_user}`)
            .then(res => res.json())
            .then(messages => {
                const body = document.getElementById('cs-chat-body');
                
                if (messages.length === 0) {
                    body.innerHTML = '<div class="text-center mt-5 text-muted">Belum ada pesan.</div>';
                    return;
                }

                let html = '';
                messages.forEach(msg => {
                    const isMe = msg.sender_role === 'admin';
                    if (isMe) {
                        html += `
                        <div class="d-flex justify-content-end mb-3">
                            <div class="p-2 px-3 rounded text-white" style="background-color: #27ae60; max-width: 75%; border-bottom-right-radius: 0 !important;">
                                <div class="mb-1">${msg.pesan}</div>
                                <div class="text-end" style="font-size: 0.65rem; color: #e9ecef;">${msg.time}</div>
                            </div>
                        </div>`;
                    } else {
                        html += `
                        <div class="d-flex justify-content-start mb-3">
                            <div class="p-2 px-3 rounded bg-light border" style="max-width: 75%; border-bottom-left-radius: 0 !important;">
                                <div class="mb-1 text-dark">${msg.pesan}</div>
                                <div class="text-end text-muted" style="font-size: 0.65rem;">${msg.time}</div>
                            </div>
                        </div>`;
                    }
                });
                
                // Only update and scroll if content changed or forced scroll
                if (body.innerHTML !== html || scrollToBottom) {
                    body.innerHTML = html;
                    if (scrollToBottom) {
                        body.scrollTop = body.scrollHeight;
                    }
                }
            });
    }

    // Send Message
    document.getElementById('cs-chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const id_user = document.getElementById('active_chat_user').value;
        const input = document.getElementById('cs_message_input');
        const message = input.value.trim();
        
        if (!id_user || !message) return;

        input.value = ''; // clear immediately for UX
        
        // Optimistic UI update
        const body = document.getElementById('cs-chat-body');
        const now = new Date();
        const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
        body.innerHTML += `
            <div class="d-flex justify-content-end mb-3">
                <div class="p-2 px-3 rounded text-white" style="background-color: #27ae60; max-width: 75%; border-bottom-right-radius: 0 !important; opacity: 0.7;">
                    <div class="mb-1">${message}</div>
                    <div class="text-end" style="font-size: 0.65rem; color: #e9ecef;">${timeStr} (Sending...)</div>
                </div>
            </div>`;
        body.scrollTop = body.scrollHeight;

        fetch(`/admin/customer-service-chat/${id_user}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ pesan: message })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                fetchMessages(id_user, true);
                fetchInbox();
            }
        });
    });

    // Auto load inbox on tab click
    document.getElementById('inbox-tab').addEventListener('shown.bs.tab', function (e) {
        fetchInbox();
        if (!chatPollingInterval && currentChatUserId) {
            chatPollingInterval = setInterval(() => {
                fetchMessages(currentChatUserId, false);
                fetchInbox();
            }, 3000);
        }
    });

    document.getElementById('inbox-tab').addEventListener('hidden.bs.tab', function (e) {
        if (chatPollingInterval) clearInterval(chatPollingInterval);
        chatPollingInterval = null;
    });

</script>
@endsection
