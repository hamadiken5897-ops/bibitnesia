/**
 * BIBITNESIA MARKETPLACE JAVASCRIPT
 * Handles all interactive features
 */

// Document Ready
document.addEventListener("DOMContentLoaded", function () {
    initCarousels();
    initFilters();
    initNotifications();
    initSidebar();
    initProductCards();
});

/**
 * Initialize Carousels
 */
function initCarousels() {
    const carousels = document.querySelectorAll(".carousel");
    carousels.forEach((carousel) => {
        new bootstrap.Carousel(carousel, {
            interval: 3000,
            ride: "carousel",
            pause: "hover",
        });
    });
}

/**
 * Initialize Filters
 */
function initFilters() {
    // Auto-submit on sort change
    const sortSelect = document.querySelector('select[name="sort"]');
    if (sortSelect) {
        sortSelect.addEventListener("change", function () {
            this.form.submit();
        });
    }

    // Price range validation
    const hargaMin = document.querySelector('input[name="harga_min"]');
    const hargaMax = document.querySelector('input[name="harga_max"]');

    if (hargaMin && hargaMax) {
        hargaMax.addEventListener("change", function () {
            const min = parseInt(hargaMin.value) || 0;
            const max = parseInt(this.value) || 0;

            if (max > 0 && max < min) {
                alert(
                    "Harga maksimum tidak boleh lebih kecil dari harga minimum!"
                );
                this.value = "";
            }
        });
    }
}

/**
 * Initialize Notifications
 */
function initNotifications() {
    const notifBtn = document.querySelector(".notification-btn");
    if (notifBtn) {
        notifBtn.addEventListener("click", function () {
            showNotificationDropdown();
        });
    }
}

/**
 * Show Notification Dropdown
 */
function showNotificationDropdown() {
    // Create dropdown if not exists
    let dropdown = document.getElementById("notification-dropdown");

    if (!dropdown) {
        dropdown = document.createElement("div");
        dropdown.id = "notification-dropdown";
        dropdown.className = "notification-dropdown";
        dropdown.innerHTML = `
            <div class="notification-header">
                <h4>Notifikasi</h4>
                <button class="btn-close-notif" onclick="closeNotificationDropdown()">×</button>
            </div>
            <div class="notification-list">
                <div class="notification-item unread">
                    <i class="fas fa-shopping-cart"></i>
                    <div>
                        <p class="notif-text">Pesanan Anda sedang diproses</p>
                        <span class="notif-time">2 menit yang lalu</span>
                    </div>
                </div>
                <div class="notification-item unread">
                    <i class="fas fa-truck"></i>
                    <div>
                        <p class="notif-text">Paket dalam perjalanan</p>
                        <span class="notif-time">1 jam yang lalu</span>
                    </div>
                </div>
                <div class="notification-item">
                    <i class="fas fa-heart"></i>
                    <div>
                        <p class="notif-text">Produk favorit Anda sedang promo!</p>
                        <span class="notif-time">3 jam yang lalu</span>
                    </div>
                </div>
            </div>
            <div class="notification-footer">
                <a href="#" class="btn-see-all">Lihat Semua Notifikasi</a>
            </div>
        `;
        document.body.appendChild(dropdown);

        // Add styles
        addNotificationStyles();
    }

    dropdown.classList.toggle("show");
}

/**
 * Close Notification Dropdown
 */
function closeNotificationDropdown() {
    const dropdown = document.getElementById("notification-dropdown");
    if (dropdown) {
        dropdown.classList.remove("show");
    }
}

/**
 * Add Notification Styles
 */
function addNotificationStyles() {
    if (!document.getElementById("notification-styles")) {
        const style = document.createElement("style");
        style.id = "notification-styles";
        style.textContent = `
            .notification-dropdown {
                position: fixed;
                top: 80px;
                right: 20px;
                width: 350px;
                background: white;
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.15);
                z-index: 1001;
                opacity: 0;
                visibility: hidden;
                transform: translateY(-10px);
                transition: all 0.3s ease;
            }
            
            .notification-dropdown.show {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }
            
            .notification-header {
                padding: 20px;
                border-bottom: 2px solid #ecf0f1;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .notification-header h4 {
                margin: 0;
                font-size: 18px;
                font-weight: 700;
                color: #2c3e50;
            }
            
            .btn-close-notif {
                background: none;
                border: none;
                font-size: 28px;
                color: #7f8c8d;
                cursor: pointer;
                line-height: 1;
            }
            
            .notification-list {
                max-height: 400px;
                overflow-y: auto;
            }
            
            .notification-item {
                display: flex;
                gap: 15px;
                padding: 15px 20px;
                border-bottom: 1px solid #ecf0f1;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .notification-item:hover {
                background: #f8f9fa;
            }
            
            .notification-item.unread {
                background: #e8f5e9;
            }
            
            .notification-item i {
                font-size: 24px;
                color: #27ae60;
            }
            
            .notif-text {
                margin: 0 0 5px 0;
                font-size: 14px;
                color: #2c3e50;
                font-weight: 500;
            }
            
            .notif-time {
                font-size: 12px;
                color: #7f8c8d;
            }
            
            .notification-footer {
                padding: 15px 20px;
                text-align: center;
            }
            
            .btn-see-all {
                color: #27ae60;
                text-decoration: none;
                font-weight: 600;
                font-size: 14px;
            }
        `;
        document.head.appendChild(style);
    }
}

/**
 * Initialize Sidebar Toggle
 */
function initSidebar() {
    // For mobile: toggle sidebar
    const menuToggle = document.getElementById("menu-toggle");
    const sidebar = document.querySelector(".sidebar");

    if (menuToggle && sidebar) {
        menuToggle.addEventListener("click", function () {
            sidebar.classList.toggle("show");
        });
    }
}

/**
 * Initialize Product Cards
 */
function initProductCards() {
    const productCards = document.querySelectorAll(".product-card");

    productCards.forEach((card) => {
        // Add click handler to redirect to detail
        const carousel = card.querySelector(".carousel");

        card.addEventListener("click", function (e) {
            // Don't redirect if clicking on carousel controls
            if (
                !e.target.closest(".carousel-control-prev") &&
                !e.target.closest(".carousel-control-next")
            ) {
                // Get product ID and redirect
                // This would be implemented based on your routing
                console.log("Redirect to product detail");
            }
        });
    });
}

/**
 * Add to Cart Function
 */
function addToCart(productId) {
    // Show loading
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menambahkan...';
    btn.disabled = true;

    // Simulate API call
    setTimeout(() => {
        btn.innerHTML = '<i class="fas fa-check"></i> Ditambahkan!';
        btn.style.background = "#27ae60";

        // Show notification
        showToast("Produk berhasil ditambahkan ke keranjang!", "success");

        // Reset button after 2 seconds
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            btn.style.background = "";
        }, 2000);
    }, 1000);
}

/**
 * Buy Now Function
 */
function buyNow(productId) {
    // Show confirmation
    if (confirm("Lanjut ke pembayaran?")) {
        // Redirect to checkout
        window.location.href = "/checkout/" + productId;
    }
}

/**
 * Show Toast Notification
 */
function showToast(message, type = "info") {
    const toast = document.createElement("div");
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
        <i class="fas fa-${
            type === "success" ? "check-circle" : "info-circle"
        }"></i>
        <span>${message}</span>
    `;

    document.body.appendChild(toast);

    // Add styles if not exists
    if (!document.getElementById("toast-styles")) {
        const style = document.createElement("style");
        style.id = "toast-styles";
        style.textContent = `
            .toast-notification {
                position: fixed;
                bottom: 30px;
                right: 30px;
                background: white;
                padding: 15px 25px;
                border-radius: 8px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.2);
                display: flex;
                align-items: center;
                gap: 12px;
                z-index: 9999;
                animation: slideInUp 0.3s ease;
            }
            
            .toast-success {
                border-left: 4px solid #27ae60;
            }
            
            .toast-success i {
                color: #27ae60;
                font-size: 20px;
            }
            
            @keyframes slideInUp {
                from {
                    transform: translateY(100px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    }

    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.animation = "slideInUp 0.3s ease reverse";
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/**
 * Search Function
 */
function performSearch() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput && searchInput.value.trim() !== "") {
        searchInput.form.submit();
    }
}

/**
 * Filter by Category
 */
function filterByCategory(category) {
    window.location.href =
        '{{ route("marketplace.index") }}?kategori=' + category;
}

/**
 * Close dropdown when clicking outside
 */
document.addEventListener("click", function (e) {
    const notifBtn = document.querySelector(".notification-btn");
    const dropdown = document.getElementById("notification-dropdown");

    if (
        dropdown &&
        !notifBtn.contains(e.target) &&
        !dropdown.contains(e.target)
    ) {
        closeNotificationDropdown();
    }
});

// Export functions for use in HTML
window.addToCart = addToCart;
window.buyNow = buyNow;
window.performSearch = performSearch;
window.filterByCategory = filterByCategory;
window.closeNotificationDropdown = closeNotificationDropdown;

const avatarToggle = document.getElementById("avatarToggle");
const avatarMenu = document.getElementById("avatarMenu");

avatarToggle.addEventListener("click", function (e) {
    e.stopPropagation();
    avatarMenu.classList.toggle("show");
});

// Klik di luar menutup menu
document.addEventListener("click", function () {
    avatarMenu.classList.remove("show");
});
