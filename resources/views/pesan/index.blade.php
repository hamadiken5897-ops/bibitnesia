@extends($layout)

@section('page-title', 'Pesan / Chat')

@section('content')
<style>
    .chat-container {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        overflow: hidden;
        border: 1px solid #e9ecef;
    }
    .chat-sidebar {
        border-right: 1px solid #e9ecef;
        height: 70vh;
        min-height: 500px;
        overflow-y: auto;
        background: #fdfdfd;
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
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(5px);
        border-bottom: 1px solid #e9ecef;
    }
    .chat-item {
        padding: 16px 20px;
        border-bottom: 1px solid #f8f9fa;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        text-decoration: none;
        color: inherit;
        position: relative;
    }
    .chat-item:hover {
        background: #f0fdf4;
    }
    .chat-item.active-chat {
        background: #e8f5e9;
        border-left: 4px solid #27ae60;
    }
    .chat-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .chat-details {
        flex-grow: 1;
        overflow: hidden;
    }
    .chat-name {
        font-weight: 600;
        margin-bottom: 2px;
        color: #2c3e50;
        font-size: 1rem;
    }
    .chat-last-message {
        font-size: 0.85rem;
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
        background: #ff4757;
        color: white;
        border-radius: 50%;
        min-width: 18px;
        height: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
        font-size: 0.65rem;
        font-weight: bold;
    }
    .chat-main {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 70vh;
        min-height: 500px;
        background: #f8f9fa;
        color: #95a5a6;
    }
    .chat-main-icon {
        font-size: 4rem;
        margin-bottom: 15px;
        color: #d1d8e0;
    }
    .chat-main-text {
        font-weight: 500;
        color: #a4b0be;
    }
    .chat-main.has-preview {
        justify-content: flex-start;
        align-items: stretch;
        padding: 0;
        background: #ffffff;
    }
    
    /* Hide the global floating chat button on the chat page */
    .floating-chat-btn {
        display: none !important;
    }
</style>

<div class="row g-0 chat-container">
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
