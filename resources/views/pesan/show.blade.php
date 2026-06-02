@extends($layout)

@section('content')
<style>
<style>
    .chat-container {
        max-width: 900px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        overflow: hidden;
        height: 78vh;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .chat-header {
        padding: 15px 25px;
        border-bottom: 1px solid #f1f2f6;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        z-index: 10;
    }
    .chat-header .back-btn {
        color: #7f8c8d;
        font-size: 1.2rem;
    }
    .chat-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        border: 2px solid #fff;
    }
    .chat-name {
        font-weight: 600;
        margin-bottom: 0;
        color: #2c3e50;
    }
    
    .chat-messages {
        flex-grow: 1;
        padding: 25px;
        overflow-y: auto;
        background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .message-bubble {
        max-width: 75%;
        padding: 12px 18px;
        border-radius: 18px;
        font-size: 0.95rem;
        position: relative;
        box-shadow: 0 3px 10px rgba(0,0,0,0.04);
        line-height: 1.5;
    }
    .message-incoming {
        align-self: flex-start;
        background: #ffffff;
        color: #2c3e50;
        border: 1px solid #f1f2f6;
        border-bottom-left-radius: 4px;
    }
    .message-outgoing {
        align-self: flex-end;
        background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        color: #ffffff;
        border-bottom-right-radius: 4px;
        box-shadow: 0 4px 15px rgba(39, 174, 96, 0.2);
    }
    .message-time {
        font-size: 0.75rem;
        margin-top: 6px;
        text-align: right;
        opacity: 0.8;
    }
    .product-context {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(0,0,0,0.03);
        padding: 8px;
        border-radius: 8px;
        margin-bottom: 8px;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .product-context img {
        width: 40px;
        height: 40px;
        border-radius: 4px;
        object-fit: cover;
    }
    .product-context-title {
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .chat-input-area {
        padding: 15px 25px;
        background: #ffffff;
        border-top: 1px solid #f1f2f6;
        box-shadow: 0 -4px 15px rgba(0,0,0,0.02);
    }
    .chat-form {
        display: flex;
        gap: 12px;
        align-items: center;
    }
    .chat-input {
        border-radius: 25px;
        padding: 12px 20px;
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    .chat-input:focus {
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(39, 174, 96, 0.1);
        border-color: #27ae60;
    }
    .btn-send {
        border-radius: 50%;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-send:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
    }
</style>

<div class="container py-4">
    <div class="chat-container border">
        
        {{-- Header --}}
        <div class="chat-header">
            <a href="{{ route('pesan.index') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
            
            @if ($lawanBicara->profile_image)
                <img src="{{ asset('storage/' . $lawanBicara->profile_image) }}" class="chat-avatar">
            @else
                <div class="chat-avatar bg-secondary text-white d-flex align-items-center justify-content-center">
                    {{ strtoupper(substr($lawanBicara->nama ?? 'U', 0, 1)) }}
                </div>
            @endif
            
            <div>
                <h6 class="chat-name">{{ $lawanBicara->nama }}</h6>
                <small class="text-muted">{{ ucfirst($lawanBicara->role) }}</small>
            </div>
        </div>
        
        {{-- Messages --}}
        <div class="chat-messages" id="chatMessages">
            @forelse($pesans as $pesan)
                @php $isMe = $pesan->id_pengirim == auth()->user()->id_user; @endphp
                
                <div class="message-bubble {{ $isMe ? 'message-outgoing' : 'message-incoming' }}">
                    
                    {{-- Context Produk jika ada --}}
                    @if($pesan->produk)
                        <a href="{{ route('marketplace.show', $pesan->id_produk) }}" class="text-decoration-none text-dark">
                            <div class="product-context {{ $isMe ? 'bg-light text-dark' : '' }}">
                                @if($pesan->produk->foto_produk1)
                                    <img src="{{ asset('storage/' . $pesan->produk->foto_produk1) }}">
                                @else
                                    <img src="https://via.placeholder.com/40">
                                @endif
                                <div>
                                    <div class="product-context-title">{{ $pesan->produk->nama_produk }}</div>
                                    <div style="font-size: 0.7rem;">Rp {{ number_format($pesan->produk->harga, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </a>
                    @endif
                    
                    <div class="message-text">{{ $pesan->isi_pesan }}</div>
                    <div class="message-time">
                        {{ $pesan->created_at->format('H:i') }}
                        @if($isMe)
                            <i class="bi {{ $pesan->is_read ? 'bi-check-all text-primary' : 'bi-check2' }} ms-1"></i>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-muted my-auto">
                    Kirim pesan pertama untuk memulai percakapan.
                </div>
            @endforelse
        </div>
        
        {{-- Input Area --}}
        <div class="chat-input-area">
            <form action="{{ route('pesan.store') }}" method="POST" class="chat-form" id="formPesan">
                @csrf
                <input type="hidden" name="id_penerima" value="{{ $lawanBicara->id_user }}">
                
                {{-- Ambil product context dari URL jika ada (Tanya Penjual) --}}
                @if(request('produk_id'))
                    <input type="hidden" name="id_produk" value="{{ request('produk_id') }}">
                @endif
                
                <input type="text" name="isi_pesan" class="form-control chat-input" placeholder="Tulis pesan..." required autocomplete="off" id="inputPesan">
                <button type="submit" class="btn-send">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16">
                      <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z"/>
                    </svg>
                </button>
            </form>
        </div>
        
    </div>
</div>

<script>
    // Scroll to bottom
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // AJAX form submission for smoother experience
    document.getElementById('formPesan').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const input = document.getElementById('inputPesan');
        const submitBtn = form.querySelector('.btn-send');
        
        if(!input.value.trim()) return;
        
        submitBtn.disabled = true;
        
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                // Refresh to show new message
                window.location.reload();
            }
        })
        .finally(() => {
            submitBtn.disabled = false;
        });
    });

    // Auto-refresh chat every 10 seconds
    setInterval(function() {
        if (!document.hidden) {
            window.location.reload();
        }
    }, 10000);
</script>
@endsection
