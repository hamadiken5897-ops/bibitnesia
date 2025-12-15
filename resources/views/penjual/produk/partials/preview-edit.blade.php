<div class="card shadow-sm preview-card">
    <div class="card-body">

        <h5 class="fw-bold mb-2">Preview Produk</h5>

        {{-- BADGE --}}
        <div class="mb-3 d-flex gap-2">
            <span class="badge bg-success">
                {{ $produk->stok > 0 ? 'Tersedia' : 'Habis' }}
            </span>
            <span class="badge bg-secondary" id="kategoriBadge">
                {{ ucfirst(str_replace('_', ' ', $produk->kategori)) }}
            </span>
        </div>

        {{-- PREVIEW BOX --}}
        <div class="preview-box" id="previewBox"
            style="
                background-image:url('{{ asset('storage/' . $produk->foto_produk1) }}');
                background-size:cover;
                background-position:center;
            ">
        </div>

        {{-- THUMBNAILS --}}
        <div class="preview-thumbs mt-3" id="previewThumbs">

            {{-- FOTO UTAMA --}}
            <div class="preview-thumb active" data-image="{{ asset('storage/' . $produk->foto_produk1) }}"
                style="background-image:url('{{ asset('storage/' . $produk->foto_produk1) }}')">
            </div>

            {{-- FOTO 2 --}}
            @if ($produk->foto_produk2)
                <div class="preview-thumb removable" data-image="{{ asset('storage/' . $produk->foto_produk2) }}"
                    data-target="foto2" style="background-image:url('{{ asset('storage/' . $produk->foto_produk2) }}')">
                    <span class="remove-thumb">&times;</span>
                </div>
            @endif

            {{-- FOTO 3 --}}
            @if ($produk->foto_produk3)
                <div class="preview-thumb removable" data-image="{{ asset('storage/' . $produk->foto_produk3) }}"
                    data-target="foto3" style="background-image:url('{{ asset('storage/' . $produk->foto_produk3) }}')">
                    <span class="remove-thumb">&times;</span>
                </div>
            @endif

        </div>

    </div>
</div>

<div class="modal fade" id="confirmRemoveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Foto?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah anda yakin akan menghapus foto ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirmRemoveBtn">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const form = document.getElementById("editProdukForm");
        if (!form) return; // ⬅️ hanya jalan di halaman edit

        let isSubmitting = false;

        form.addEventListener("submit", function(e) {

            if (isSubmitting) return;

            e.preventDefault();

            Swal.fire({
                title: "Update Produk?",
                text: "Perubahan akan disimpan",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#198754",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Ya, Update",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    isSubmitting = true;
                    form.submit(); // ✅ submit normal (CSRF aman)
                }
            });
        });

    });
</script>
