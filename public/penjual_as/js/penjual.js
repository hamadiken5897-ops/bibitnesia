document.addEventListener("DOMContentLoaded", () => {
    const previewBox = document.getElementById("previewBox");
    const previewEmpty = document.getElementById("previewEmpty");
    const thumbs = document.getElementById("previewThumbs");
    const form = document.getElementById("produkForm");
    const backBtn = document.getElementById("btnKembali");

    if (!form) return; // ⛔ bukan halaman create

    // ===============================
    // STATE FOTO (CREATE ONLY)
    // ===============================
    const images = {
        foto_produk1: null,
        foto_produk2: null,
        foto_produk3: null,
    };

    function renderPreview() {
        thumbs.innerHTML = "";

        const files = Object.values(images).filter(Boolean);

        if (!files.length) {
            previewBox.style.backgroundImage = "";
            previewBox.style.border = "2px dashed #cfe3d8";
            if (previewEmpty) previewEmpty.style.display = "block";
            return;
        }

        // preview utama
        previewBox.style.backgroundImage = `url('${files[0]}')`;
        previewBox.style.backgroundSize = "cover";
        previewBox.style.border = "none";
        if (previewEmpty) previewEmpty.style.display = "none";

        Object.entries(images).forEach(([key, src]) => {
            if (!src) return;

            const thumb = document.createElement("div");
            thumb.className = "preview-thumb";
            thumb.style.backgroundImage = `url('${src}')`;

            thumb.onclick = () => {
                previewBox.style.backgroundImage = `url('${src}')`;
            };

            // tombol hapus
            const remove = document.createElement("span");
            remove.className = "remove-thumb";
            remove.innerHTML = "&times;";
            remove.onclick = (e) => {
                e.stopPropagation();
                images[key] = null;

                const input = document.querySelector(`input[name="${key}"]`);
                if (input) input.value = "";

                renderPreview();
            };

            thumb.appendChild(remove);
            thumbs.appendChild(thumb);
        });
    }

    // ===============================
    // INPUT FILE
    // ===============================
    ["foto_produk1", "foto_produk2", "foto_produk3"].forEach((name) => {
        const input = document.querySelector(`input[name="${name}"]`);
        if (!input) return;

        input.addEventListener("change", function () {
            if (!this.files[0]) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                images[name] = e.target.result;
                renderPreview();
            };
            reader.readAsDataURL(this.files[0]);
        });
    });

    // ===============================
    // BADGE KATEGORI
    // ===============================
    const kategoriSelect = document.querySelector('select[name="kategori"]');
    const kategoriBadge = document.getElementById("kategoriBadge");

    if (kategoriSelect && kategoriBadge) {
        kategoriSelect.addEventListener("change", function () {
            kategoriBadge.textContent = this.options[this.selectedIndex].text;
        });
    }

    // ===============================
    // VALIDASI & SUBMIT (CREATE)
    // ===============================
    let isDirty = false;

    form.querySelectorAll("input, textarea, select").forEach((el) => {
        el.addEventListener("input", () => (isDirty = true));
        el.addEventListener("change", () => (isDirty = true));
    });

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const nama = form.nama_produk.value.trim();
        const kategori = form.kategori.value;
        const harga = form.harga.value;
        const stok = form.stok.value;

        let errors = [];

        if (!nama) errors.push("Nama produk wajib diisi");
        if (!kategori) errors.push("Kategori wajib dipilih");
        if (!harga || harga <= 0) errors.push("Harga harus > 0");
        if (!stok || stok < 0) errors.push("Stok tidak valid");
        const fotoUtamaInput = form.querySelector('input[name="foto_produk1"]');
        const fotoUtamaLama = form.dataset.hasFotoUtama === "1";

        if (!fotoUtamaLama && !fotoUtamaInput.files.length) {
            errors.push("Foto utama wajib diupload");
        }

        if (errors.length) {
            Swal.fire({
                icon: "error",
                title: "Data belum lengkap",
                html: `<ul style="text-align:left">${errors
                    .map((e) => `<li>${e}</li>`)
                    .join("")}</ul>`,
            });
            return;
        }

        Swal.fire({
            title: "Simpan Produk?",
            text: "Pastikan data sudah benar",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#198754",
            confirmButtonText: "Ya, Simpan",
        }).then((res) => {
            if (res.isConfirmed) {
                isDirty = false;
                form.submit();
            }
        });
    });

    // ===============================
    // UNSAVED CHANGES
    // ===============================
    if (backBtn) {
        backBtn.addEventListener("click", (e) => {
            if (!isDirty) return;

            e.preventDefault();
            Swal.fire({
                title: "Perubahan belum disimpan",
                text: "Yakin ingin keluar?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#198754",
            }).then((res) => {
                if (res.isConfirmed) {
                    window.location.href = backBtn.href;
                }
            });
        });
    }

    window.addEventListener("beforeunload", (e) => {
        if (isDirty) {
            e.preventDefault();
            e.returnValue = "";
        }
    });
});
