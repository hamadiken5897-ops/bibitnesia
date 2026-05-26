@extends('layouts.admin.admin')

@section('title', 'Manajemen Komplain & Banned')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Manajemen Layanan Pengguna</h3>
    </div>
</div>

<div class="page-content">
    <!-- Tampilkan Notifikasi Sukses/Error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible show fade">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header pb-0">
            <!-- Nav Tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active font-weight-bold" id="komplain-tab" data-bs-toggle="tab" data-bs-target="#komplain" type="button" role="tab" aria-controls="komplain" aria-selected="true">
                        <i class="bi bi-chat-left-text-fill"></i> Laporan & Komplain Pengguna
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link font-weight-bold" id="banned-tab" data-bs-toggle="tab" data-bs-target="#banned" type="button" role="tab" aria-controls="banned" aria-selected="false">
                        <i class="bi bi-slash-circle-fill"></i> Daftar Pengguna Dibanned
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content pt-3" id="myTabContent">
                
                <!-- TAB 1: LAPORAN & KOMPLAIN -->
                <div class="tab-pane fade show active" id="komplain" role="tabpanel" aria-labelledby="komplain-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID Komplain</th>
                                    <th>Pelapor</th>
                                    <th>Terlapor</th>
                                    <th>Judul Laporan</th>
                                    <th>Status</th>
                                    <th>Tanggal Masuk</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($komplains as $komplain)
                                    <tr>
                                        <td><span class="font-monospace text-primary">{{ $komplain->id_komplain }}</span></td>
                                        <td>
                                            <strong>{{ $komplain->user->nama ?? 'Tidak Diketahui' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ ucfirst($komplain->user->role ?? '-') }}</small>
                                        </td>
                                        <td>
                                            @if($komplain->terlapor)
                                                <strong>{{ $komplain->terlapor->nama }}</strong>
                                                <br>
                                                <small class="text-muted">{{ ucfirst($komplain->terlapor->role) }}</small>
                                                @if($komplain->terlapor->status_akun === 'BANNED')
                                                    <span class="badge bg-danger ms-1">BANNED</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="d-inline-block text-truncate" style="max-width: 200px;">
                                                {{ $komplain->judul_laporan }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($komplain->status === 'MENUNGGU')
                                                <span class="badge bg-warning text-dark">Menunggu</span>
                                            @elseif($komplain->status === 'DIPROSES')
                                                <span class="badge bg-info">Diproses</span>
                                            @elseif($komplain->status === 'SELESAI')
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-secondary">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>{{ $komplain->created_at ? $komplain->created_at->format('d M Y H:i') : '-' }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $komplain->id_komplain }}">
                                                    <i class="bi bi-eye-fill"></i> Detail & Proses
                                                </button>
                                                @if($komplain->id_terlapor && $komplain->terlapor && $komplain->terlapor->status_akun !== 'BANNED')
                                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#bannedModal{{ $komplain->id_komplain }}">
                                                        <i class="bi bi-slash-circle-fill"></i> Banned Terlapor
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- MODAL DETAIL & PROSES -->
                                    <div class="modal fade" id="detailModal{{ $komplain->id_komplain }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">Detail Komplain - {{ $komplain->id_komplain }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <h6 class="text-primary border-bottom pb-2">Informasi Pelapor (User)</h6>
                                                            <p class="mb-1"><strong>Nama:</strong> {{ $komplain->user->nama ?? '-' }}</p>
                                                            <p class="mb-1"><strong>Email:</strong> {{ $komplain->user->email ?? '-' }}</p>
                                                            <p class="mb-1"><strong>Telepon:</strong> {{ $komplain->user->no_telepon ?? '-' }}</p>
                                                            <p class="mb-1"><strong>Role:</strong> {{ ucfirst($komplain->user->role ?? '-') }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6 class="text-primary border-bottom pb-2">Informasi Terlapor (Jika Ada)</h6>
                                                            @if($komplain->terlapor)
                                                                <p class="mb-1"><strong>Nama:</strong> {{ $komplain->terlapor->nama }}</p>
                                                                <p class="mb-1"><strong>Email:</strong> {{ $komplain->terlapor->email }}</p>
                                                                <p class="mb-1"><strong>Telepon:</strong> {{ $komplain->terlapor->no_telepon }}</p>
                                                                <p class="mb-1"><strong>Role:</strong> {{ ucfirst($komplain->terlapor->role) }}</p>
                                                                <p class="mb-1"><strong>Status Akun:</strong> 
                                                                    @if($komplain->terlapor->status_akun === 'BANNED')
                                                                        <span class="badge bg-danger">BANNED</span>
                                                                    @else
                                                                        <span class="badge bg-success">AKTIF</span>
                                                                    @endif
                                                                </p>
                                                            @else
                                                                <p class="text-muted">Tidak ada pihak yang dilaporkan secara spesifik.</p>
                                                            @endif
                                                        </div>

                                                        <div class="col-12 mt-4">
                                                            <h6 class="text-primary border-bottom pb-2">Detail Kasus & Masalah</h6>
                                                            @if($komplain->id_pesanan)
                                                                <p class="mb-2"><strong>ID Pesanan Terkait:</strong> 
                                                                    <a href="{{ route('admin.pembayaran') }}?search={{ $komplain->id_pesanan }}" class="font-monospace text-decoration-none">
                                                                        {{ $komplain->id_pesanan }}
                                                                    </a>
                                                                </p>
                                                            @endif
                                                            <p class="mb-1"><strong>Judul Keluhan:</strong></p>
                                                            <p class="bg-light p-2 rounded border font-weight-bold">{{ $komplain->judul_laporan }}</p>
                                                            
                                                            <p class="mb-1"><strong>Isi Keluhan:</strong></p>
                                                            <div class="bg-light p-3 rounded border" style="white-space: pre-wrap;">{{ $komplain->deskripsi_laporan }}</div>
                                                        </div>

                                                        @if($komplain->bukti_foto)
                                                            <div class="col-12 mt-3">
                                                                <h6 class="text-primary border-bottom pb-2">Bukti Foto</h6>
                                                                <div>
                                                                    <a href="{{ asset('storage/' . $komplain->bukti_foto) }}" target="_blank">
                                                                        <img src="{{ asset('storage/' . $komplain->bukti_foto) }}" class="img-thumbnail" style="max-height: 250px; object-fit: contain;">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <!-- FORM PROSES ADMIN -->
                                                        <div class="col-12 mt-4 pt-3 border-top">
                                                            <h6 class="text-primary mb-3">Tanggapan & Pemrosesan Laporan</h6>
                                                            <form action="{{ route('admin.komplain.status', $komplain->id_komplain) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label class="form-label font-weight-bold">Status Komplain</label>
                                                                        <select name="status" class="form-select">
                                                                            <option value="MENUNGGU" {{ $komplain->status === 'MENUNGGU' ? 'selected' : '' }}>MENUNGGU</option>
                                                                            <option value="DIPROSES" {{ $komplain->status === 'DIPROSES' ? 'selected' : '' }}>DIPROSES</option>
                                                                            <option value="SELESAI" {{ $komplain->status === 'SELESAI' ? 'selected' : '' }}>SELESAI</option>
                                                                            <option value="DITOLAK" {{ $komplain->status === 'DITOLAK' ? 'selected' : '' }}>DITOLAK</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-12 mb-3">
                                                                        <label class="form-label font-weight-bold">Catatan / Tindak Lanjut Admin</label>
                                                                        <textarea name="catatan_admin" class="form-control" rows="3" placeholder="Tuliskan respon atau tindakan yang telah diambil terkait laporan ini...">{{ $komplain->catatan_admin }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="text-end">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                    <button type="submit" class="btn btn-success">
                                                                        <i class="bi bi-save"></i> Simpan Perubahan
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- MODAL BANNED USER TERLAPOR -->
                                    @if($komplain->id_terlapor && $komplain->terlapor && $komplain->terlapor->status_akun !== 'BANNED')
                                        <div class="modal fade" id="bannedModal{{ $komplain->id_komplain }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">Banned Pengguna Terlapor</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.komplain.ban', $komplain->id_komplain) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="alert alert-warning">
                                                                <strong>Peringatan!</strong> Tindakan ini akan memblokir akun <strong>{{ $komplain->terlapor->nama }}</strong> ({{ $komplain->id_terlapor }}) sehingga mereka tidak bisa login ke dalam sistem.
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Tipe Pemblokiran</label>
                                                                <select name="banned_status" class="form-select" id="banned_status_{{ $komplain->id_komplain }}" onchange="toggleDatepicker('{{ $komplain->id_komplain }}')">
                                                                    <option value="PERMANEN">PERMANEN</option>
                                                                    <option value="SEMENTARA">SEMENTARA</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3 d-none" id="date_picker_container_{{ $komplain->id_komplain }}">
                                                                <label class="form-label">Waktu Berakhir Pemblokiran (Tanggal & Jam)</label>
                                                                <input type="datetime-local" name="tgl_berakhir" class="form-control">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Alasan Pemblokiran / Pelanggaran</label>
                                                                <textarea name="alasan" class="form-control" rows="3" required placeholder="Tuliskan alasan pemblokiran secara detail..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="bi bi-slash-circle"></i> Eksekusi Banned
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Tidak ada laporan komplain dari pengguna.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB 2: PENGGUNA DIBANNED -->
                <div class="tab-pane fade" id="banned" role="tabpanel" aria-labelledby="banned-tab">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID Banned</th>
                                    <th>Pengguna</th>
                                    <th>Tipe Banned</th>
                                    <th>Waktu Banned</th>
                                    <th>Waktu Berakhir</th>
                                    <th>Alasan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($banneds as $ban)
                                    <tr>
                                        <td><span class="font-monospace text-danger">{{ $ban->id_banned }}</span></td>
                                        <td>
                                            <strong>{{ $ban->user->nama ?? 'Tidak Diketahui' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $ban->id_user }} ({{ ucfirst($ban->user->role ?? '-') }})</small>
                                        </td>
                                        <td>
                                            @if($ban->status === 'PERMANEN')
                                                <span class="badge bg-danger">Permanen</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Sementara</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($ban->tgl_banned)->format('d M Y H:i') }}</td>
                                        <td>
                                            @if($ban->status === 'PERMANEN')
                                                <span class="text-danger">Selamanya</span>
                                            @else
                                                {{ \Carbon\Carbon::parse($ban->tgl_berakhir)->format('d M Y H:i') }}
                                            @endif
                                        </td>
                                        <td>
                                            <span class="d-inline-block text-truncate" style="max-width: 250px;" title="{{ $ban->alasan }}">
                                                {{ $ban->alasan }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <!-- Form Unban -->
                                            <button type="button" class="btn btn-success btn-sm" onclick="confirmUnban('{{ $ban->id_user }}', '{{ $ban->user->nama ?? $ban->id_user }}')">
                                                <i class="bi bi-unlock-fill"></i> Buka Blokir (Unban)
                                            </button>
                                            <form id="unban-form-{{ $ban->id_user }}" action="{{ route('admin.banned.unban', $ban->id_user) }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Tidak ada pengguna yang sedang diblokir (banned).</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleDatepicker(id) {
        const select = document.getElementById('banned_status_' + id);
        const dateContainer = document.getElementById('date_picker_container_' + id);
        if (select.value === 'SEMENTARA') {
            dateContainer.classList.remove('d-none');
            dateContainer.querySelector('input').setAttribute('required', 'true');
        } else {
            dateContainer.classList.add('d-none');
            dateContainer.querySelector('input').removeAttribute('required');
        }
    }

    function confirmUnban(userId, userName) {
        Swal.fire({
            title: 'Buka Blokir Pengguna?',
            text: "Apakah Anda yakin ingin memulihkan status akun " + userName + "?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Buka Blokir',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('unban-form-' + userId).submit();
            }
        });
    }
</script>
@endsection
