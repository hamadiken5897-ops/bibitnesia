<div class="card shadow-sm preview-card w-100">
    <div class="card-body">

        <h5 class="fw-bold mb-2">Preview Produk</h5>

        {{-- BADGE --}}
        <div class="mb-3 d-flex gap-2">
            <span class="badge bg-success">Tersedia</span>
            <span class="badge bg-secondary" id="kategoriBadge">Kategori</span>
        </div>

        {{-- PREVIEW BOX --}}
        <div class="preview-box" id="previewBox">
            <div class="preview-empty" id="previewEmpty">
                <i class="bi bi-cloud-upload"></i>
                <p>Upload Foto Produk</p>
                <span>Foto utama akan tampil di sini</span>
            </div>
        </div>

        <div id="previewThumbs" class="preview-thumbs"></div>
    </div>
</div>
