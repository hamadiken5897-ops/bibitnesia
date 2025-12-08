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
                <button class="btn btn-danger" onclick="rejectMitra({{ $data->id_pengajuan }})">
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

<div class="modal fade" id="rejectModal{{ $data->id_pengajuan }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Tolak Pengajuan</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div id="errorNote{{ $data->id_pengajuan }}" class="alert alert-danger d-none">
                    Catatan wajib diisi.
                </div>

                <label>Catatan Admin *</label>
                <textarea id="note{{ $data->id_pengajuan }}" class="form-control"></textarea>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>

                <button class="btn btn-danger" onclick="validateNote({{ $data->id_pengajuan }})">Lanjut</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="confirmReject{{ $data->id_pengajuan }}">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5 class="modal-title">Konfirmasi</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                Yakin ingin menolak pengajuan ini?
            </div>

            <div class="modal-footer">

                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>

                <form action="{{ route('admin.pengajuan.reject', $data->id_pengajuan) }}" method="POST">
                    @csrf
                    <input type="hidden" name="catatan_admin" id="finalNote{{ $data->id_pengajuan }}">
                    <button class="btn btn-danger">Tolak Sekarang</button>
                </form>

            </div>

        </div>
    </div>
</div>

<script>
    function rejectMitra(id) {

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

            Swal.fire({
                title: "Yakin ingin menolak?",
                icon: "warning",
                showCancelButton: true,
                cancelButtonText: "Batal",
                confirmButtonText: "Ya, Tolak",
                confirmButtonColor: "#d33"
            }).then((confirmResult) => {

                if (confirmResult.isConfirmed) {

                    // Kirim data via form dinamis
                    let form = document.createElement("form");
                    form.method = "POST";
                    form.action = "/admin/pengajuan-mitra/" + id + "/reject";

                    let csrf = document.createElement("input");
                    csrf.type = "hidden";
                    csrf.name = "_token";
                    csrf.value = "{{ csrf_token() }}";

                    let note = document.createElement("input");
                    note.type = "hidden";
                    note.name = "catatan_admin";
                    note.value = catatan;

                    form.appendChild(csrf);
                    form.appendChild(note);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });

    }
</script>
