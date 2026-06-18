@extends($layout)

@section('content')
<style>
    .chat-container {
        max-width: 1100px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .chat-sidebar {
        border-right: 1px solid #f1f2f6;
        height: 75vh;
        overflow-y: auto;
        background: #fafbfc;
    }
    .chat-sidebar::-webkit-scrollbar {
        width: 6px;
    }
    .chat-sidebar::-webkit-scrollbar-thumb {
        background-color: #dcdde1;
        border-radius: 4px;
    }
    .chat-sidebar-header {
        position: sticky;
        top: 0;
        z-index: 10;
        background: rgba(250, 251, 252, 0.95);
        backdrop-filter: blur(5px);
        border-bottom: 1px solid #f1f2f6;
    }
    .chat-item {
        padding: 18px 20px;
        border-bottom: 1px solid #f8f9fa;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        text-decoration: none;
        color: inherit;
        position: relative;
    }
    .chat-item:hover {
        background: #ffffff;
        transform: translateX(5px);
        box-shadow: -5px 0 15px rgba(0,0,0,0.02);
        z-index: 1;
    }
    .chat-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
        border: 2px solid #fff;
        box-shadow: 0 3px 8px rgba(0,0,0,0.08);
    }
    .chat-details {
        flex-grow: 1;
        overflow: hidden;
    }
    .chat-name {
        font-weight: 700;
        margin-bottom: 3px;
        color: #2c3e50;
        font-size: 1.05rem;
    }
    .chat-last-message {
        font-size: 0.9rem;
        color: #7f8c8d;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .chat-meta {
        text-align: right;
        font-size: 0.75rem;
        color: #95a5a6;
        font-weight: 500;
    }
    .badge-unread {
        background: linear-gradient(135deg, #ff6b6b, #ee5253);
        color: white;
        border-radius: 50%;
        min-width: 20px;
        height: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 6px;
        font-size: 0.7rem;
        font-weight: bold;
        box-shadow: 0 2px 5px rgba(238, 82, 83, 0.4);
        animation: pulse-badge 2s infinite;
    }
    @keyframes pulse-badge {
        0% { box-shadow: 0 0 0 0 rgba(238, 82, 83, 0.4); }
        70% { box-shadow: 0 0 0 6px rgba(238, 82, 83, 0); }
        100% { box-shadow: 0 0 0 0 rgba(238, 82, 83, 0); }
    }
    .chat-main {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 75vh;
        background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
        color: #95a5a6;
    }
    .chat-main-icon {
        font-size: 5rem;
        margin-bottom: 20px;
        background: -webkit-linear-gradient(#27ae60, #2ecc71);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        opacity: 0.5;
        filter: drop-shadow(0 10px 10px rgba(0,0,0,0.05));
    }
    .chat-main-text {
        font-weight: 600;
        color: #7f8c8d;
    }
    .chat-main.has-preview {
        justify-content: flex-start;
        align-items: stretch;
        padding: 0;
    }
    
    /* Hide the global floating chat button on the chat page */
    .floating-chat-btn {
        display: none !important;
    }
</style>

<div class="container py-4">
    <h4 class="fw-bold mb-4">Pesan / Chat</h4>
    
    <div class="row g-0 chat-container border">
        <!-- Sidebar Kontaks -->
        <div class="col-md-4 chat-sidebar p-0">
            <div class="p-4 chat-sidebar-header d-flex align-items-center">
                <i class="bi bi-chat-square-dots-fill text-success fs-4 me-2"></i>
                <h5 class="mb-0 fw-bold text-dark">Pesan Masuk</h5>
            </div>
            
            @if(empty($kontaks))
                <div class="p-5 text-center text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                    Belum ada percakapan.
                </div>
            @else
                @foreach($kontaks as $id => $data)
                    <a href="{{ route('pesan.show', $id) }}" class="chat-item">
                        @if ($data['user']->profile_image)
                            <img src="{{ asset('storage/' . $data['user']->profile_image) }}" class="chat-avatar">
                        @else
                            <div class="chat-avatar bg-success bg-gradient text-white d-flex align-items-center justify-content-center fw-bold fs-5">
                                {{ strtoupper(substr($data['user']->nama ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                        
                        <div class="chat-details">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="chat-name">{{ $data['user']->nama }}</div>
                                <div class="chat-meta">{{ $data['pesan_terakhir']->created_at->format('H:i') }}</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <div class="chat-last-message">
                                    @if($data['pesan_terakhir']->id_pengirim == auth()->user()->id_user)
                                        <i class="bi bi-check2-all text-success me-1"></i>Anda: 
                                    @endif
                                    {{ $data['pesan_terakhir']->isi_pesan }}
                                </div>
                                @if($data['unread'] > 0)
                                    <span class="badge-unread">{{ $data['unread'] }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
        
        <!-- Main Chat Area (Empty State) -->
        <div class="col-md-8 chat-main d-none d-md-flex">
            <i class="bi bi-chat-heart chat-main-icon"></i>
            <h5 class="chat-main-text">Pilih percakapan untuk mulai chat</h5>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let clickTimer = null;
        const delay = 300; // milliseconds to distinguish between single and double click
        
        document.querySelectorAll('.chat-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                const href = this.getAttribute('href');
                const chatMain = document.querySelector('.chat-main');
                
                if (clickTimer === null) {
                    // Start timer for single click (Preview)
                    clickTimer = setTimeout(() => {
                        clickTimer = null;
                        
                        // Show loading state
                        chatMain.classList.remove('has-preview');
                        chatMain.innerHTML = '<div class="spinner-border text-success" role="status"><span class="visually-hidden">Loading...</span></div>';
                        
                        // Fetch preview
                        const previewUrl = href + (href.includes('?') ? '&' : '?') + 'preview=1';
                        
                        fetch(previewUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.text())
                        .then(html => {
                            chatMain.classList.add('has-preview');
                            chatMain.innerHTML = html;
                        })
                        .catch(err => {
                            chatMain.innerHTML = '<div class="text-danger">Gagal memuat preview pesan.</div>';
                        });
                        
                    }, delay);
                } else {
                    // Double click detected (Enter chat)
                    clearTimeout(clickTimer);
                    clickTimer = null;
                    
                    // Navigate to the chat page
                    window.location.href = href;
                }
            });
        });
    });
</script>
@endsection
