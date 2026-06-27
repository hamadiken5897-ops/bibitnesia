<div id="global-loader" style="position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(4px); z-index: 99999; display: none; opacity: 0; align-items: center; justify-content: center; transition: opacity 0.3s ease;">
    <div class="text-center">
        <div class="spinner-border text-success mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
        <h5 class="text-success fw-bold">BibitNesia</h5>
        <p class="text-muted small mb-0">Memuat halaman...</p>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const loader = document.getElementById('global-loader');
        if (loader) {
            // Hanya tampilkan loader di awal jika berasal dari klik sidebar
            if (sessionStorage.getItem('showLoader') === 'true') {
                loader.style.display = 'flex';
                // Trigger reflow
                void loader.offsetWidth;
                loader.style.opacity = '1';
                
                window.addEventListener('load', function() {
                    loader.style.opacity = '0';
                    setTimeout(() => { loader.style.display = 'none'; }, 300);
                });
                
                // Hapus state agar reload biasa tidak menampilkan loader
                sessionStorage.removeItem('showLoader');
            }
            
            // Tangkap klik HANYA pada link di dalam sidebar
            document.querySelectorAll('.sidebar-menu a:not([target="_blank"]):not([href^="#"]):not([href^="javascript:"])').forEach(link => {
                link.addEventListener('click', function(e) {
                    // Cek jika link tidak mencegah default action (misal JS event)
                    if (!e.defaultPrevented && this.href && this.href !== window.location.href + '#') {
                        // Tampilkan loader saat halaman ini mau pindah
                        loader.style.display = 'flex';
                        void loader.offsetWidth;
                        loader.style.opacity = '1';
                        
                        // Set state agar di halaman berikutnya loader tetap tampil sampai selesai muat
                        sessionStorage.setItem('showLoader', 'true');
                    }
                });
            });
        }
    });
</script>
