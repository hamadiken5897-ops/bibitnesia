<div class="modal fade" id="detailModal{{ $data->id_pengajuan }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Detail Pengajuan {{ ucfirst($role) }}</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <h5>Informasi User</h5>
                <p><strong>Nama:</strong> {{ $data->user->nama }}</p>
                <p><strong>No HP:</strong> {{ $data->no_hp }}</p>
                <p><strong>Alamat:</strong> {{ $data->alamat }}</p>

                <hr>

                <h5>Dokumen</h5>
                <p>KTP: <a href="{{ asset('storage/' . $data->ktp) }}" target="_blank">Lihat</a></p>
                <p>Selfie: <a href="{{ asset('storage/' . $data->foto_selfie) }}" target="_blank">Lihat</a></p>

                @if ($role == 'penjual')
                    <hr>
                    <h5>Data Penjual</h5>
                    <p>Alamat Kebun: {{ $data->alamat_kebun }}</p>
                    <p>Deskripsi: {{ $data->deskripsi }}</p>

                    <h6 class="mt-3">Foto Kebun</h6>
                    @if ($data->foto_kebun)
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $data->foto_kebun) }}" target="_blank">
                                <img src="{{ asset('storage/' . $data->foto_kebun) }}" class="rounded border"
                                    style="width:180px; height:120px; object-fit:cover;">
                            </a>
                        </div>
                    @else
                        <p class="text-muted">Tidak ada foto kebun.</p>
                    @endif

                    <h6 class="mt-3">Portofolio</h6>
                    @if ($data->portofolio)
                        <div class="mb-2">
                            <a href="{{ asset('storage/' . $data->portofolio) }}" target="_blank">
                                <i class="bi bi-file-earmark-text"></i> Lihat Portofolio
                            </a>
                        </div>
                    @else
                        <p class="text-muted">Tidak ada portofolio.</p>
                    @endif
                @endif

                @if ($role == 'kurir')
                    <hr>
                    <h5>Dokumen Kurir</h5>
                    <p>SIM: <a href="{{ asset('storage/' . $data->sim) }}" target="_blank">Lihat</a></p>
                    <p>STNK: <a href="{{ asset('storage/' . $data->stnk) }}" target="_blank">Lihat</a></p>
                    <p>Foto Kendaraan: <a href="{{ asset('storage/' . $data->foto_kendaraan) }}"
                            target="_blank">Lihat</a></p>

                    <h5 class="mt-3">Data Kendaraan</h5>
                    <p>Tipe Kendaraan: {{ $data->tipe_kendaraan }}</p>
                    <p>Merek: {{ $data->merek_kendaraan }}</p>
                @endif
            </div>

            <div class="modal-footer">
                <button class="btn btn-danger" onclick="openReject({{ $data->id_pengajuan }})">
                    Tolak
                </button>


                <form action="{{ route('admin.pengajuan.approve', $data->id_pengajuan) }}" method="POST">
                    @csrf
                    <button class="btn btn-success">Setujui</button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    function openReject(id) {
        // Tutup modal Bootstrap detail
        const modal = bootstrap.Modal.getInstance(document.getElementById('detailModal' + id));
        modal.hide();

        // Beri sedikit delay agar backdrop hilang dulu
        setTimeout(() => {
            rejectMitra(id);
        }, 300);
    }

    function rejectMitra(id) {

        // Step 1: Input alasan
        Swal.fire({
            title: "Alasan Penolakan",
            input: "textarea",
            inputPlaceholder: "Tuliskan alasan penolakan di sini...",
            inputAttributes: {
                required: true
            },
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonText: "Lanjutkan",
            confirmButtonColor: "#d33",
            preConfirm: (value) => {
                if (!value) {
                    Swal.showValidationMessage("Catatan wajib diisi");
                }
                return value;
            }
        }).then((result) => {

            if (!result.isConfirmed) return;

            let catatan = result.value;

            // Step 2: Konfirmasi akhir
            Swal.fire({
                title: "Yakin ingin menolak pengajuan ini?",
                icon: "warning",
                showCancelButton: true,
                cancelButtonText: "Batal",
                confirmButtonText: "Ya, Tolak",
                confirmButtonColor: "#d33"
            }).then((confirmResult) => {

                if (!confirmResult.isConfirmed) return;

                // Step 3: Submit form (tanpa modal bootstrap)
                let form = document.createElement("form");
                form.method = "POST";
                form.action = "/admin/pengajuan-mitra/" + id + "/reject";

                // CSRF
                let csrf = document.createElement("input");
                csrf.type = "hidden";
                csrf.name = "_token";
                csrf.value = "{{ csrf_token() }}";

                // Note
                let note = document.createElement("input");
                note.type = "hidden";
                note.name = "catatan_admin";
                note.value = catatan;

                form.appendChild(csrf);
                form.appendChild(note);

                document.body.appendChild(form);
                form.submit();
            });
        });
    }
</script>
