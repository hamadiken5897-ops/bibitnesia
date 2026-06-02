<script src="{{ asset('dist/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('dist/assets/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('dist/assets/vendors/apexcharts/apexcharts.js') }}"></script>
<script src="{{ asset('dist/assets/js/pages/dashboard.js') }}"></script>
<script src="{{ asset('penjual_as/js/penjual.js') }}"></script>
<script src="{{ asset('dist/assets/js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function fetchAdminUnreadChat() {
            fetch("{{ route('pesan.unread') }}", {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('sidebarChatBadge');
                if (badge) {
                    if (data.unread > 0) {
                        badge.style.display = 'inline-block';
                        badge.innerText = data.unread > 99 ? '99+' : data.unread;
                    } else {
                        badge.style.display = 'none';
                    }
                }
            })
            .catch(err => console.log('Error fetching unread chat count'));
        }

        // Fetch on load
        fetchAdminUnreadChat();

        // Polling every 15 seconds
        setInterval(fetchAdminUnreadChat, 15000);
    });
</script>