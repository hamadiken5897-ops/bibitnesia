<style>
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
        width: 100%;
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
        width: 100%;
        height: calc(75vh - 75px - 40px); /* Adjust height to fit header and footer */
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
    .preview-footer {
        padding: 10px;
        text-align: center;
        width: 100%;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        font-size: 0.85rem;
        color: #6c757d;
    }
</style>

<div class="chat-header w-100">
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

<div class="chat-messages w-100" id="previewMessages">
    @forelse($pesans as $pesan)
        @php $isMe = $pesan->id_pengirim == auth()->user()->id_user; @endphp
        
        <div class="message-bubble {{ $isMe ? 'message-outgoing' : 'message-incoming' }}">
            {{-- Context Produk jika ada --}}
            @if($pesan->produk)
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
        <div class="text-center text-muted my-auto w-100">
            Belum ada pesan.
        </div>
    @endforelse
</div>

<div class="preview-footer">
    <i class="bi bi-info-circle me-1"></i> Tekan dua kali pada chat untuk masuk dan membalas pesan.
</div>

<script>
    // Scroll to bottom of preview
    setTimeout(() => {
        const previewMsgs = document.getElementById('previewMessages');
        if(previewMsgs) previewMsgs.scrollTop = previewMsgs.scrollHeight;
    }, 100);
</script>
