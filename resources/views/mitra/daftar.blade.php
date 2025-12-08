<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Mitra Bibitnesia</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('form/css/form.css') }}">
</head>

<body>

    <div class="container-form">

        <h2 class="title"><i class="bi bi-person-check"></i> Pendaftaran Mitra Bibitnesia</h2>
        <p class="text-muted">Isi data berikut untuk mendaftar sebagai Penjual atau Kurir Bibitnesia.</p>

        <!-- ======================================== -->
        <!-- VALIDATION -->
        <!-- ======================================== -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Periksa kembali form Anda:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ======================================== -->
        <!-- MULTI STEP PROGRESS -->
        <!-- ======================================== -->
        <div class="progress-container">
            <div class="progress-line"></div>
            <div class="progress-line-fill" id="progressLine"></div>

            <div class="step-circle" id="circle1">1</div>
            <div class="step-circle" id="circle2">2</div>
            <div class="step-circle" id="circle3">3</div>
        </div>

        <!-- ======================================== -->
        <!-- FORM -->
        <!-- ======================================== -->
        <form action="{{ route('pengajuan-mitra.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if (isset($role))
                <input type="hidden" name="role_pengajuan" value="{{ $role }}">
            @endif

            <!-- ============================== -->
            <!-- STEP 1 — PILIH ROLE + DATA UMUM -->
            <!-- ============================== -->
            <div class="step" id="step1">

                @unless (isset($role))
                    <div class="input-group">
                        <label>Daftar sebagai *</label>
                        <select name="role_pengajuan" id="role_pengajuan" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="penjual">Penjual</option>
                            <option value="kurir">Kurir</option>
                        </select>
                    </div>
                @endunless

                <h5 class="section-title mt-4">Data Umum</h5>

                <div class="input-group">
                    <label>Alamat *</label>
                    <textarea name="alamat" required></textarea>
                </div>

                <div class="input-group">
                    <label>No HP *</label>
                    <input type="text" name="no_hp" required>
                </div>

                <button type="button" class="btn btn-primary w-100 mt-4" onclick="nextStep()">
                    Lanjutkan <i class="bi bi-arrow-right"></i>
                </button>
            </div>

            <!-- ============================== -->
            <!-- STEP 2 — DOKUMEN WAJIB -->
            <!-- ============================== -->
            <div class="step hidden" id="step2">

                <h5 class="section-title">Dokumen Wajib</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <label>KTP *</label>
                            <input type="file" name="ktp" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group">
                            <label>Foto Selfie *</label>
                            <input type="file" name="foto_selfie" required>
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <label>No Rekening *</label>
                    <input type="text" name="no_rekening" required>
                </div>

                <button type="button" class="btn btn-primary w-100 mt-2" onclick="nextStep()">
                    Lanjutkan <i class="bi bi-arrow-right"></i>
                </button>

                <button type="button" class="btn btn-secondary w-100 mt-4" onclick="prevStep()">
                    <i class="bi bi-arrow-left"></i> Kembali
                </button>

            </div>

            <!-- ============================== -->
            <!-- STEP 3 — PENJUAL / KURIR -->
            <!-- ============================== -->
            <div class="step hidden" id="step3">

                <!-- PENJUAL -->
                <div id="penjualFields" class="hidden">
                    <h5 class="section-title">Data Penjual Berkebun</h5>

                    <div class="input-group">
                        <label>Foto Kebun</label>
                        <input type="file" name="foto_kebun">
                    </div>

                    <div class="input-group">
                        <label>Portofolio (PDF/DOC)</label>
                        <input type="file" name="portofolio">
                    </div>

                    <div class="input-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi"></textarea>
                    </div>

                    <div class="input-group">
                        <label>Alamat Kebun</label>
                        <input type="text" name="alamat_kebun">
                    </div>
                </div>

                <!-- KURIR -->
                <div id="kurirFields" class="hidden">
                    <h5 class="section-title">Data Kendaraan Kurir</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <label>SIM</label>
                                <input type="file" name="sim">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <label>STNK</label>
                                <input type="file" name="stnk">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <label>SKCK</label>
                                <input type="file" name="skck">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <label>Foto Kendaraan</label>
                                <input type="file" name="foto_kendaraan">
                            </div>
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Tipe Kendaraan</label>
                        <select name="tipe_kendaraan">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                            <option value="truk">Truk</option>
                            <option value="cargo">Cargo</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Merek Kendaraan</label>
                        <input type="text" name="merek_kendaraan">
                    </div>
                </div>

                <button class="btn btn-primary w-100 mt-3">
                    <i class="bi bi-check-circle"></i> Kirim Pengajuan
                </button>

                <button type="button" class="btn btn-secondary w-100 mt-4" onclick="prevStep()">
                    <i class="bi bi-arrow-left"></i> Kembali
                </button>

            </div>
        </form>
    </div>

    <!-- Custom JS -->
    <script src="{{ asset('form/js/form.js') }}"></script>

</body>
</html>
