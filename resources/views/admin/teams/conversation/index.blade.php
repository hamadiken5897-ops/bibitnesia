@extends('layouts.admin.admin')

@section('title', 'Team Conversation')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Team Conversation</h3>
                <p class="text-subtitle text-muted">Ruang obrolan internal untuk semua staff administrator.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Team Conversation</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title text-white mb-0"><i class="bi bi-chat-dots-fill me-2"></i> Admin Group Chat</h4>
            </div>
            <div class="card-body p-0">
                <div class="chat-container d-flex flex-column" style="height: 60vh;">
                    
                    {{-- Area Pesan --}}
                    <div id="chat-messages" class="flex-grow-1 p-4" style="overflow-y: auto; background-color: #f8f9fa;">
                        {{-- AJAX akan inject pesan di sini --}}
                        <div class="text-center text-muted mt-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>

                    {{-- Form Input Pesan --}}
                    <div class="chat-input border-top p-3 bg-white">
                        <form id="chat-form">
                            @csrf
                            <div class="input-group">
                                <input type="text" id="pesan-input" class="form-control" placeholder="Ketik pesan..." required autocomplete="off">
                                <button class="btn btn-primary" type="submit" id="btn-send">
                                    <i class="bi bi-send-fill"></i> Kirim
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const pesanInput = document.getElementById('pesan-input');
    const btnSend = document.getElementById('btn-send');

    // Fungsi fetch pesan
    function fetchMessages() {
        fetch("{{ route('admin.conversation.fetch') }}")
            .then(res => res.text())
            .then(html => {
                const isScrolledToBottom = chatMessages.scrollHeight - chatMessages.clientHeight <= chatMessages.scrollTop + 50;
                
                chatMessages.innerHTML = html;

                if (isScrolledToBottom) {
                    scrollToBottom();
                }
            })
            .catch(err => console.error("Gagal memuat pesan:", err));
    }

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Polling setiap 3 detik
    setInterval(fetchMessages, 3000);

    // Initial fetch
    fetchMessages();
    setTimeout(scrollToBottom, 500); // Scroll down on first load

    // Submit form AJAX
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const pesan = pesanInput.value.trim();
        if(!pesan) return;

        btnSend.disabled = true;

        fetch("{{ route('admin.conversation.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ pesan: pesan })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                pesanInput.value = '';
                fetchMessages();
                setTimeout(scrollToBottom, 300);
            }
        })
        .catch(err => console.error("Gagal mengirim:", err))
        .finally(() => {
            btnSend.disabled = false;
            pesanInput.focus();
        });
    });
</script>
@endsection
