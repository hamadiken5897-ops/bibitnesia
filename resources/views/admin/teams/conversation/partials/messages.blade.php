@forelse($messages as $message)
    @php
        $isMe = $message->id_user === auth()->user()->id_user;
    @endphp

    <div class="d-flex {{ $isMe ? 'justify-content-end' : 'justify-content-start' }} mb-3">
        @if(!$isMe)
            <div class="me-2">
                @if(optional($message->user->file)->file_stream)
                    <img src="{{ $message->user->file->file_stream }}" class="rounded-circle" width="40" height="40" alt="{{ $message->user->nama }}">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($message->user->nama) }}&background=random" class="rounded-circle" width="40" height="40" alt="{{ $message->user->nama }}">
                @endif
            </div>
        @endif

        <div class="{{ $isMe ? 'bg-primary text-white' : 'bg-light text-dark' }} p-3 rounded" style="max-width: 70%;">
            @if(!$isMe)
                <div class="fw-bold text-success mb-1" style="font-size: 0.85rem;">{{ $message->user->nama }}</div>
            @endif
            <div style="font-size: 0.95rem;">
                {{ $message->pesan }}
            </div>
            <div class="{{ $isMe ? 'text-white-50' : 'text-muted' }} mt-1 text-end" style="font-size: 0.75rem;">
                {{ $message->created_at->format('H:i') }}
            </div>
        </div>
    </div>
@empty
    <div class="text-center text-muted mt-5">
        <i class="bi bi-chat-square-dots fs-1"></i>
        <p class="mt-2">Belum ada pesan di grup ini. Mulailah percakapan!</p>
    </div>
@endforelse
