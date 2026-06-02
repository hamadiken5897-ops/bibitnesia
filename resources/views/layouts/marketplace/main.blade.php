<!DOCTYPE html>
<html lang="id">

<head>
    @include('layouts.marketplace.partials.head')
</head>

<body>

    {{-- SIDEBAR --}}
    @include('layouts.marketplace.partials.sidebar')

    <div class="main-content">

        {{-- HEADER --}}
        @include('layouts.marketplace.partials.header')

        {{-- PAGE CONTENT --}}
        @yield('content')

    </div>

    {{-- SCRIPTS --}}
    @include('layouts.marketplace.partials.scripts')

    @auth
    {{-- FLOATING CHAT BUBBLE --}}
    <style>
        .floating-chat-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.4), 0 0 0 0 rgba(39, 174, 96, 0.4);
            cursor: pointer;
            z-index: 9999;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-decoration: none;
            animation: floating-pulse 2s infinite;
        }
        
        @keyframes floating-pulse {
            0% { box-shadow: 0 4px 15px rgba(39, 174, 96, 0.4), 0 0 0 0 rgba(39, 174, 96, 0.4); }
            70% { box-shadow: 0 4px 15px rgba(39, 174, 96, 0.4), 0 0 0 15px rgba(39, 174, 96, 0); }
            100% { box-shadow: 0 4px 15px rgba(39, 174, 96, 0.4), 0 0 0 0 rgba(39, 174, 96, 0); }
        }
        
        .floating-chat-btn:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.6);
            color: white;
            animation: none;
        }
        
        .floating-chat-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: linear-gradient(135deg, #ff6b6b, #ee5253);
            color: white;
            font-size: 13px;
            font-weight: 700;
            min-width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
            border-radius: 20px;
            border: 3px solid #ffffff;
            box-shadow: 0 3px 8px rgba(0,0,0,0.15);
            display: none; /* hidden by default */
        }
    </style>

    <a href="{{ route('pesan.index') }}" class="floating-chat-btn" title="Pesan / Chat">
        <i class="bi bi-chat-dots-fill"></i>
        <span class="floating-chat-badge" id="chatUnreadBadge">0</span>
    </a>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function fetchUnreadChat() {
                fetch("{{ route('pesan.unread') }}", {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('chatUnreadBadge');
                    if (data.unread > 0) {
                        badge.style.display = 'block';
                        badge.innerText = data.unread > 99 ? '99+' : data.unread;
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(err => console.log('Error fetching unread chat count'));
            }

            // Fetch on load
            fetchUnreadChat();

            // Polling every 15 seconds
            setInterval(fetchUnreadChat, 15000);
        });
    </script>
    @endauth

</body>
</html>
