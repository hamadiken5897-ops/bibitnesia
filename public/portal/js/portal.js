/* ========================================
   BIBITNESIA PORTAL JAVASCRIPT
   ======================================== */

// Smooth Scroll Navigation
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute("href"));
        if (target) {
            const navHeight = document.querySelector(".navbar").offsetHeight;
            const targetPosition = target.offsetTop - navHeight;
            window.scrollTo({
                top: targetPosition,
                behavior: "smooth",
            });
        }
    });
});

// Navbar background shadow on scroll
window.addEventListener("scroll", function () {
    const navbar = document.querySelector(".navbar");
    if (window.scrollY > 50) {
        navbar.style.boxShadow = "0 4px 20px rgba(0,0,0,0.15)";
    } else {
        navbar.style.boxShadow = "0 2px 10px rgba(0,0,0,0.1)";
    }
});

// Add active class to nav links on scroll
window.addEventListener("scroll", function () {
    const sections = document.querySelectorAll("section[id]");
    const navLinks = document.querySelectorAll(".nav-link-custom");

    let current = "";
    sections.forEach((section) => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (pageYOffset >= sectionTop - 100) {
            current = section.getAttribute("id");
        }
    });

    navLinks.forEach((link) => {
        link.classList.remove("active");
        if (link.getAttribute("href") === "#" + current) {
            link.classList.add("active");
        }
    });
});

// Product card hover effects (optional enhancement)
document.addEventListener("DOMContentLoaded", function () {
    const productCards = document.querySelectorAll(".product-card");

    productCards.forEach((card) => {
        card.addEventListener("mouseenter", function () {
            this.style.cursor = "pointer";
        });
    });

    // Feature cards animation on scroll (optional)
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
    };

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = "1";
                entry.target.style.transform = "translateY(0)";
            }
        });
    }, observerOptions);

    // Observe feature cards
    const featureCards = document.querySelectorAll(".feature-card");
    featureCards.forEach((card) => {
        card.style.opacity = "0";
        card.style.transform = "translateY(20px)";
        card.style.transition = "opacity 0.6s ease, transform 0.6s ease";
        observer.observe(card);
    });
});

// Auto-close mobile menu when clicking a link
document.querySelectorAll(".nav-link-custom").forEach((link) => {
    link.addEventListener("click", function () {
        const navbarToggler = document.querySelector(".navbar-toggler");
        const navbarCollapse = document.querySelector(".navbar-collapse");

        if (
            window.innerWidth < 992 &&
            navbarCollapse.classList.contains("show")
        ) {
            navbarToggler.click();
        }
    });
});

// Carousel auto-play control (optional)
const carousel = document.querySelector("#heroCarousel");
if (carousel) {
    // Pause carousel on hover
    carousel.addEventListener("mouseenter", function () {
        const bsCarousel = bootstrap.Carousel.getInstance(carousel);
        if (bsCarousel) {
            bsCarousel.pause();
        }
    });

    // Resume carousel on mouse leave
    carousel.addEventListener("mouseleave", function () {
        const bsCarousel = bootstrap.Carousel.getInstance(carousel);
        if (bsCarousel) {
            bsCarousel.cycle();
        }
    });
}

// Lazy loading images (optional enhancement)
if ("IntersectionObserver" in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove("lazy");
                imageObserver.unobserve(img);
            }
        });
    });

    const images = document.querySelectorAll("img.lazy");
    images.forEach((img) => imageObserver.observe(img));
}

// Console log for debugging (remove in production)
console.log("Bibitnesia Portal - Initialized ✓");

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".notif-item").forEach((el) => {
        el.addEventListener("click", function () {
            let title = this.dataset.judul;
            let pesan = this.dataset.pesan;
            let url = this.dataset.url;
            let id = this.dataset.id;

            Swal.fire({
                title: title,
                html: pesan,
                icon: "info",
                confirmButtonText: "Lanjutkan",
            }).then(() => {
                // Redirect ke halaman atau tab bersangkutan
                window.location.href = url;
            });
        });
    });
});
