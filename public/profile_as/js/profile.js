/* ========================================
   PROFILE PAGE JAVASCRIPT
   File: public/js/profile.js
   ======================================== */

/**
 * Toggle Edit Form
 * Show/Hide inline edit form
 */
function toggleEditForm() {
    const editContainer = document.getElementById('edit-form-container');
    
    if (editContainer) {
        const isHidden = editContainer.style.display === 'none' || editContainer.style.display === '';
        
        if (isHidden) {
            // Show form
            editContainer.style.display = 'block';
            
            // Scroll to form
            editContainer.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'nearest' 
            });
        } else {
            // Hide form
            editContainer.style.display = 'none';
        }
    }
}

/**
 * Character Counter for Textarea
 * Update character count for deskripsi field
 */
document.addEventListener('DOMContentLoaded', function() {
    const deskripsiTextarea = document.querySelector('textarea[name="deskripsi"]');
    
    if (deskripsiTextarea) {
        deskripsiTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            const maxLength = 500;
            const counter = this.nextElementSibling;
            
            if (counter && counter.classList.contains('text-muted')) {
                counter.textContent = `${currentLength}/${maxLength} karakter`;
                
                // Warning jika mendekati limit
                if (currentLength >= maxLength * 0.9) {
                    counter.classList.remove('text-muted');
                    counter.classList.add('text-warning');
                } else {
                    counter.classList.remove('text-warning');
                    counter.classList.add('text-muted');
                }
            }
        });
    }
});

/**
 * Image Preview
 * Preview gambar sebelum upload
 */
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('input[name="profile_image"]');
    
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Validasi ukuran file (max 2MB)
                const maxSize = 2 * 1024 * 1024; // 2MB in bytes
                
                if (file.size > maxSize) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB.');
                    this.value = ''; // Reset input
                    return;
                }
                
                // Validasi tipe file
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak didukung! Gunakan JPG, JPEG, atau PNG.');
                    this.value = ''; // Reset input
                    return;
                }
                
                // Preview image (optional)
                const reader = new FileReader();
                reader.onload = function(event) {
                    // Bisa ditambahkan preview image di sini jika diperlukan
                    console.log('Image loaded:', event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

/**
 * Confirm Before Delete/Cancel
 * Konfirmasi sebelum membatalkan edit
 */
function confirmCancel() {
    const form = document.querySelector('#edit-form-container form');
    
    if (form) {
        // Check if form has been modified
        const formData = new FormData(form);
        let hasChanges = false;
        
        for (let [key, value] of formData.entries()) {
            if (value !== '') {
                hasChanges = true;
                break;
            }
        }
        
        if (hasChanges) {
            return confirm('Anda yakin ingin membatalkan? Perubahan tidak akan disimpan.');
        }
    }
    
    return true;
}

/**
 * Auto-dismiss Alert
 * Otomatis menutup alert setelah beberapa detik
 */
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-dismissible');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000); // 5 detik
    });
});

/**
 * Smooth Tab Switching
 * Animasi halus saat ganti tab
 */
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('[data-bs-toggle="tab"]');
    
    tabLinks.forEach(link => {
        link.addEventListener('shown.bs.tab', function(event) {
            // Scroll to tab content
            const targetId = event.target.getAttribute('data-bs-target');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                setTimeout(() => {
                    targetElement.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'nearest' 
                    });
                }, 100);
            }
        });
    });
});

/**
 * Lazy Load Images
 * Load gambar produk secara lazy untuk performa
 */
document.addEventListener('DOMContentLoaded', function() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });
        
        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => imageObserver.observe(img));
    }
});

/**
 * Copy Profile Link
 * Copy link profil ke clipboard
 */
function copyProfileLink() {
    const profileUrl = window.location.href;
    
    navigator.clipboard.writeText(profileUrl).then(() => {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'alert alert-success position-fixed bottom-0 end-0 m-3';
        toast.innerHTML = '<i class="bi bi-check-circle me-2"></i>Link profil berhasil disalin!';
        toast.style.zIndex = '9999';
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }).catch(err => {
        console.error('Gagal menyalin link:', err);
        alert('Gagal menyalin link. Silakan copy manual dari address bar.');
    });
}

/**
 * Share Profile
 * Share profil ke media sosial
 */
function shareProfile(platform) {
    const profileUrl = encodeURIComponent(window.location.href);
    const profileName = document.querySelector('.profile-name')?.textContent || 'Profil';
    const shareText = encodeURIComponent(`Lihat profil ${profileName} di Bibitnesia`);
    
    let shareUrl = '';
    
    switch(platform) {
        case 'whatsapp':
            shareUrl = `https://wa.me/?text=${shareText}%20${profileUrl}`;
            break;
        case 'facebook':
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${profileUrl}`;
            break;
        case 'twitter':
            shareUrl = `https://twitter.com/intent/tweet?text=${shareText}&url=${profileUrl}`;
            break;
        default:
            console.error('Platform tidak didukung');
            return;
    }
    
    window.open(shareUrl, '_blank', 'width=600,height=400');
}

/**
 * Form Validation
 * Validasi form sebelum submit
 */
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.querySelector('#edit-form-container form');
    
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            const nama = editForm.querySelector('input[name="nama"]');
            const noTelepon = editForm.querySelector('input[name="no_telepon"]');
            
            // Validasi nama (required)
            if (!nama.value.trim()) {
                e.preventDefault();
                alert('Nama tidak boleh kosong!');
                nama.focus();
                return false;
            }
            
            // Validasi nomor telepon (optional tapi harus valid jika diisi)
            if (noTelepon.value.trim()) {
                const phonePattern = /^[0-9+\-\s()]+$/;
                if (!phonePattern.test(noTelepon.value)) {
                    e.preventDefault();
                    alert('Format nomor telepon tidak valid!');
                    noTelepon.focus();
                    return false;
                }
            }
            
            // Show loading state
            const submitBtn = editForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
            }
        });
    }
});

/**
 * Print Profile
 * Print halaman profil
 */
function printProfile() {
    window.print();
}

// Export functions untuk digunakan di tempat lain jika diperlukan
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        toggleEditForm,
        copyProfileLink,
        shareProfile,
        printProfile,
        confirmCancel
    };
}