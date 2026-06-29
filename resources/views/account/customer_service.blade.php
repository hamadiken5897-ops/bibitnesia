@extends('account.layout')

@section('account_content')
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-3 px-4">
        <h5 class="card-title fw-bold text-success mb-0">
            <i class="fas fa-headset me-2"></i> Live Chat Customer Service
        </h5>
        <p class="text-muted small mt-1 mb-0">Hubungi kami jika Anda memiliki kendala atau pertanyaan mengenai layanan BibitNesia.</p>
    </div>
    
    <div class="card-body p-0 d-flex flex-column" style="height: 500px;">
        <div class="flex-grow-1 p-4 bg-light" id="user-cs-chat-body" style="overflow-y: auto;">
            <div class="text-center mt-5">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
        
        <div class="p-3 border-top bg-white rounded-bottom-4">
            <form id="user-cs-chat-form" class="d-flex">
                <input type="text" id="user_cs_message_input" class="form-control rounded-pill px-4 me-2" placeholder="Ketik pesan Anda..." autocomplete="off">
                <button type="submit" class="btn btn-success rounded-pill px-4"><i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
</div>

<script>
    let chatPollingInterval = null;

    function fetchMessages(scrollToBottom = true) {
        fetch('{{ route('account.cs.fetch') }}', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(res => res.json())
            .then(messages => {
                const body = document.getElementById('user-cs-chat-body');
                
                if (messages.length === 0) {
                    body.innerHTML = '<div class="text-center mt-5 text-muted">Belum ada pesan. Mulai obrolan dengan Customer Service sekarang.</div>';
                    return;
                }

                let html = '';
                messages.forEach(msg => {
                    const isMe = msg.sender_role === 'user';
                    if (isMe) {
                        html += `
                        <div class="d-flex justify-content-end mb-3">
                            <div class="p-3 rounded-4 text-white shadow-sm" style="background-color: #27ae60; max-width: 80%; border-bottom-right-radius: 4px !important;">
                                <div class="mb-1">${msg.pesan}</div>
                                <div class="text-end" style="font-size: 0.65rem; color: #e9ecef;">${msg.time}</div>
                            </div>
                        </div>`;
                    } else {
                        html += `
                        <div class="d-flex justify-content-start mb-3">
                            <div class="p-3 rounded-4 bg-white shadow-sm border" style="max-width: 80%; border-bottom-left-radius: 4px !important;">
                                <div class="mb-1 text-dark">${msg.pesan}</div>
                                <div class="text-end text-muted" style="font-size: 0.65rem;">${msg.time}</div>
                            </div>
                        </div>`;
                    }
                });
                
                if (body.innerHTML !== html || scrollToBottom) {
                    body.innerHTML = html;
                    if (scrollToBottom) {
                        body.scrollTop = body.scrollHeight;
                    }
                }
            })
            .catch(error => {
                console.error("Fetch error: ", error);
                const body = document.getElementById('user-cs-chat-body');
                body.innerHTML = `<div class="text-center mt-5 text-danger">Gagal memuat pesan. Error: ${error.message}</div>`;
            });
    }

    document.getElementById('user-cs-chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('user_cs_message_input');
        const message = input.value.trim();
        
        if (!message) return;
        input.value = ''; // clear input
        
        // Optimistic update
        const body = document.getElementById('user-cs-chat-body');
        const now = new Date();
        const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
        
        // Remove empty state if present
        if (body.innerHTML.includes('Belum ada pesan')) {
            body.innerHTML = '';
        }
        
        body.innerHTML += `
            <div class="d-flex justify-content-end mb-3">
                <div class="p-3 rounded-4 text-white shadow-sm" style="background-color: #27ae60; max-width: 80%; border-bottom-right-radius: 4px !important; opacity: 0.7;">
                    <div class="mb-1">${message}</div>
                    <div class="text-end" style="font-size: 0.65rem; color: #e9ecef;">${timeStr} (Sending...)</div>
                </div>
            </div>`;
        body.scrollTop = body.scrollHeight;

        fetch('{{ route('account.cs.send') }}', {
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
                fetchMessages(true);
            }
        });
    });

    // Initial fetch and start polling
    document.addEventListener("DOMContentLoaded", function() {
        fetchMessages();
        chatPollingInterval = setInterval(() => {
            fetchMessages(false);
        }, 3000);
    });
</script>
@endsection
