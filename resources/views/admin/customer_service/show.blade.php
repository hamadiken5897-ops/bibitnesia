@extends('layouts.admin.admin')

@section('title', 'Detail Laporan Customer Service')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Review Laporan / Investigasi</h3>
        <a href="{{ route('admin.customer_service.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="page-content">
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

    <div class="row">
        <!-- Kolom Kiri: Detail Laporan -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title text-primary fw-bold mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Laporan</h5>
                </div>
                <div class="card-body mt-3">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Pelapor</h6>
                            <p class="font-weight-bold">{{ $komplain->user->nama ?? '-' }} ({{ ucfirst($komplain->user->role ?? '-') }})</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Tanggal Masuk</h6>
                            <p>{{ $komplain->created_at ? $komplain->created_at->format('d M Y H:i') : '-' }}</p>
                        </div>
                    </div>
                    
                    <h6 class="text-muted">Kategori Laporan</h6>
                    <p><span class="badge bg-secondary">{{ ucfirst($komplain->kategori_laporan ?? 'Umum') }}</span></p>

                    <h6 class="text-muted">Judul Laporan</h6>
                    <p class="font-weight-bold">{{ $komplain->judul_laporan }}</p>

                    <h6 class="text-muted">Deskripsi Laporan</h6>
                    <div class="bg-light p-3 rounded mb-3" style="white-space: pre-wrap;">{{ $komplain->deskripsi_laporan }}</div>

                    @if($komplain->bukti_foto)
                        <h6 class="text-muted">Bukti Foto</h6>
                        <a href="{{ asset('storage/' . $komplain->bukti_foto) }}" target="_blank">
                            <img src="{{ asset('storage/' . $komplain->bukti_foto) }}" alt="Bukti Laporan" class="img-thumbnail" style="max-height: 250px;">
                        </a>
                    @endif
                </div>
            </div>

            <!-- Detail Objek Terkait -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title text-info fw-bold mb-0"><i class="bi bi-link-45deg me-2"></i>Objek Terkait</h5>
                </div>
                <div class="card-body mt-3">
                    @if($komplain->id_ulasan && $komplain->ulasan)
                        <h6>Detail Ulasan yang Dilaporkan</h6>
                        <div class="border p-3 rounded bg-light mb-3">
                            <strong>{{ $komplain->ulasan->user->nama ?? '-' }}</strong> <small class="text-muted">{{ $komplain->ulasan->created_at->format('d M Y') }}</small>
                            <br>
                            <div>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star-fill {{ $i <= $komplain->ulasan->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                            <p class="mt-2 mb-0">{{ $komplain->ulasan->komentar }}</p>
                        </div>
                    @elseif($komplain->id_produk && $komplain->produk)
                        <h6>Detail Produk yang Dilaporkan</h6>
                        <div class="card shadow-sm border mb-3">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="{{ $komplain->produk->foto_produk1 ? asset('storage/' . $komplain->produk->foto_produk1) : asset('dist/assets/images/samples/error-404.svg') }}" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="Foto Produk">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title"><a href="{{ route('marketplace.show', $komplain->produk->id_produk) }}" target="_blank">{{ $komplain->produk->nama_produk }}</a></h5>
                                        <p class="card-text mb-1"><strong>Penjual:</strong> {{ optional($komplain->produk->penjual)->user->nama ?? '-' }}</p>
                                        <p class="card-text mb-1"><strong>Harga:</strong> Rp {{ number_format($komplain->produk->harga, 0, ',', '.') }}</p>
                                        <p class="card-text mb-1"><strong>Stok:</strong> {{ $komplain->produk->stok }}</p>
                                        <p class="card-text mb-2 text-truncate" style="max-height: 3rem;">{{ $komplain->produk->deskripsi }}</p>
                                        <p class="card-text">
                                            @if($komplain->produk->status == 'dihapus_admin')
                                                <span class="badge bg-danger">DIHAPUS ADMIN</span>
                                            @elseif($komplain->produk->status == 'hidden')
                                                <span class="badge bg-warning text-dark">DISEMBUNYIKAN ADMIN</span>
                                            @else
                                                <span class="badge bg-success">{{ strtoupper($komplain->produk->status) }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6>Utas Produk Terkait (Komentar & Ulasan)</h6>
                        <div class="border rounded bg-light p-2 mb-3" style="max-height: 300px; overflow-y: auto;">
                            @if($komplain->produk->ulasans && $komplain->produk->ulasans->count() > 0)
                                @foreach($komplain->produk->ulasans->sortByDesc('created_at') as $ulasanProduk)
                                    <div class="mb-2 p-2 bg-white border rounded {{ $komplain->id_ulasan == $ulasanProduk->id_ulasan ? 'border-danger border-2 shadow-sm' : '' }}">
                                        @if($komplain->id_ulasan == $ulasanProduk->id_ulasan)
                                            <span class="badge bg-danger float-end">Ulasan Dilaporkan</span>
                                        @endif
                                        <strong>{{ optional($ulasanProduk->user)->nama ?? 'Anonim' }}</strong> <small class="text-muted">{{ $ulasanProduk->created_at->format('d M Y H:i') }}</small>
                                        <div>
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star-fill {{ $i <= $ulasanProduk->rating ? 'text-warning' : 'text-muted' }} small"></i>
                                            @endfor
                                        </div>
                                        <p class="mb-0 small">{{ $ulasanProduk->komentar }}</p>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted small mb-0 p-2">Belum ada ulasan untuk produk ini.</p>
                            @endif
                        </div>
                        <div class="mt-3">
                            @if(optional(auth()->user()->admin)->jabatan === 'super_admin')
                                <form action="{{ route('admin.produk.destroy', $komplain->produk->id_produk) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="alasan_admin" value="Melanggar ketentuan (Dari laporan {{ $komplain->id_komplain }})">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini secara paksa? (Akan menunggu 7 hari sebelum terhapus permanen dari DB)')">
                                        <i class="bi bi-trash"></i> Hapus Produk
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.produk.update', $komplain->produk->id_produk) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="hidden">
                                <input type="hidden" name="alasan_admin" value="Melanggar ketentuan (Dari laporan {{ $komplain->id_komplain }})">
                                <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Apakah Anda yakin ingin menyembunyikan produk ini?')">
                                    <i class="bi bi-eye-slash"></i> Sembunyikan Produk
                                </button>
                            </form>
                        </div>
                    @elseif($komplain->id_terlapor && $komplain->terlapor)
                        <h6>Pengguna yang Dilaporkan</h6>
                        <p><strong>Nama:</strong> <a href="{{ route('profile.show', $komplain->terlapor->id_user) }}" target="_blank">{{ $komplain->terlapor->nama }}</a></p>
                        <p><strong>Email:</strong> {{ $komplain->terlapor->email }}</p>
                        <p><strong>Role:</strong> {{ ucfirst($komplain->terlapor->role) }}</p>
                    @elseif($komplain->id_pesanan)
                        <h6>Pesanan Terkait</h6>
                        <p><strong>ID Pesanan:</strong> <a href="{{ route('admin.pembayaran') }}?search={{ $komplain->id_pesanan }}" class="font-monospace text-decoration-none">{{ $komplain->id_pesanan }}</a></p>
                    @else
                        <p class="text-muted">Tidak ada objek spesifik yang ditautkan.</p>
                    @endif
                </div>
            </div>

            <!-- Analisis Perilaku Penjual / Terlapor -->
            @php
                $targetUser = $komplain->terlapor ?? ($komplain->produk ? optional($komplain->produk->penjual)->user : ($komplain->ulasan ? $komplain->ulasan->user : null));
            @endphp

            @if($targetUser)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold mb-0" style="color: #d35400;"><i class="bi bi-activity me-2"></i>Analisis Perilaku <span class="text-muted fw-normal fs-6">(Target: {{ $targetUser->nama }})</span></h5>
                </div>
                <div class="card-body mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Ringkasan Akun</h6>
                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#profileModal">
                            <i class="bi bi-person-lines-fill"></i> Lihat Detail Profil
                        </button>
                    </div>
                    <ul>
                        <li><strong>Status Akun:</strong> {{ $targetUser->status_akun }}</li>
                        <li><strong>Peringatan Aktif:</strong> {!! $targetUser->peringatan_teks ? '<span class="text-danger font-weight-bold">Ya</span> (Sampai ' . \Carbon\Carbon::parse($targetUser->tgl_peringatan)->addDays(5)->format('d M Y') . ')' : '<span class="text-success">Tidak ada</span>' !!}</li>
                    </ul>

                    @if($targetUser->role == 'penjual')
                        <h6 class="mt-4">Aktivitas Penjual</h6>
                        <ul class="nav nav-tabs" id="activityTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pesanan-tab" data-bs-toggle="tab" data-bs-target="#pesanan" type="button" role="tab">Pesanan & Pengiriman</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="chat-tab" data-bs-toggle="tab" data-bs-target="#chat" type="button" role="tab">Riwayat Chat</button>
                            </li>
                        </ul>
                        <div class="tab-content border border-top-0 p-3 mb-4 bg-white" id="activityTabsContent" style="max-height: 400px; overflow-y: auto;">
                            <!-- Tab Pesanan -->
                            <div class="tab-pane fade show active" id="pesanan" role="tabpanel">
                                @php
                                    $pesanans = optional($targetUser->penjual)->id_penjual 
                                        ? \App\Models\Pesanan::whereHas('detailPesanan.produk', function ($q) use ($targetUser) {
                                            $q->where('id_penjual', $targetUser->penjual->id_penjual);
                                          })->with(['user', 'pengiriman'])->orderByDesc('created_at')->take(10)->get()
                                        : collect();
                                @endphp
                                @if($pesanans->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ID Pesanan</th>
                                                    <th>Tgl</th>
                                                    <th>Pembeli</th>
                                                    <th>Pesanan</th>
                                                    <th>Pengiriman</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pesanans as $p)
                                                <tr>
                                                    <td><a href="{{ route('admin.pembayaran') }}?search={{ $p->id_pesanan }}" target="_blank">{{ $p->id_pesanan }}</a></td>
                                                    <td>{{ $p->created_at->format('d M y') }}</td>
                                                    <td>{{ optional($p->user)->nama ?? '-' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $p->status_pesanan == 'dibatalkan' ? 'danger' : ($p->status_pesanan == 'selesai' ? 'success' : 'warning text-dark') }}">{{ $p->status_pesanan }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary">{{ optional($p->pengiriman)->status_pengiriman ?? 'Belum ada' }}</span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted small mb-0">Belum ada pesanan.</p>
                                @endif
                            </div>
                            
                            <!-- Tab Chat -->
                            <div class="tab-pane fade" id="chat" role="tabpanel">
                                @php
                                    $pesanMasuk = collect($targetUser->pesanDiterima ?? []);
                                    $pesanKeluar = collect($targetUser->pesanTerkirim ?? []);
                                    $semuaChat = $pesanMasuk->concat($pesanKeluar)->sortByDesc('created_at')->take(15);
                                @endphp
                                @if($semuaChat->count() > 0)
                                    @foreach($semuaChat as $chat)
                                        @php
                                            $isMasuk = $chat->id_penerima == $targetUser->id_user;
                                            $lawan = $isMasuk ? optional($chat->pengirim)->nama : optional($chat->penerima)->nama;
                                        @endphp
                                        <div class="mb-3 border-bottom pb-2">
                                            <div class="d-flex justify-content-between">
                                                <small class="fw-bold text-{{ $isMasuk ? 'primary' : 'success' }}">
                                                    <i class="bi bi-arrow-{{ $isMasuk ? 'down-right' : 'up-right' }}"></i> 
                                                    {{ $isMasuk ? 'Dari: ' . $lawan : 'Ke: ' . $lawan }}
                                                </small>
                                                <small class="text-muted">{{ $chat->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                            <p class="mb-0 small mt-1">{{ $chat->isi_pesan }}</p>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted small mb-0">Belum ada riwayat chat.</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <h6>Riwayat Laporan Terhadap Pengguna Ini</h6>
                    @if($riwayatLaporan && $riwayatLaporan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tanggal</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayatLaporan as $riwayat)
                                    <tr>
                                        <td><a href="{{ route('admin.customer_service.show', $riwayat->id_komplain) }}">{{ $riwayat->id_komplain }}</a></td>
                                        <td>{{ $riwayat->created_at->format('d M Y') }}</td>
                                        <td>{{ ucfirst($riwayat->kategori_laporan) }}</td>
                                        <td>{{ $riwayat->status }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-success">Belum ada riwayat laporan sebelumnya.</p>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Kolom Kanan: Aksi Admin -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title text-dark fw-bold mb-0"><i class="bi bi-shield-lock me-2"></i>Tindakan Admin</h5>
                </div>
                <div class="card-body mt-3">
                    <!-- Update Status Komplain -->
                    <form action="{{ route('admin.customer_service.status', $komplain->id_komplain) }}" method="POST" class="mb-4 pb-4 border-bottom">
                        @csrf
                        @method('PUT')
                        <h6 class="font-weight-bold">Status Penyelesaian</h6>
                        <select name="status" class="form-select mb-2">
                            <option value="MENUNGGU" {{ $komplain->status === 'MENUNGGU' ? 'selected' : '' }}>MENUNGGU</option>
                            <option value="DIPROSES" {{ $komplain->status === 'DIPROSES' ? 'selected' : '' }}>DIPROSES</option>
                            <option value="SELESAI" {{ $komplain->status === 'SELESAI' ? 'selected' : '' }}>SELESAI</option>
                            <option value="DITOLAK" {{ $komplain->status === 'DITOLAK' ? 'selected' : '' }}>DITOLAK</option>
                        </select>
                        <textarea name="catatan_admin" class="form-control mb-2" rows="3" placeholder="Catatan internal penyelesaian...">{{ $komplain->catatan_admin }}</textarea>
                        <button type="submit" class="btn btn-primary w-100">Simpan Status</button>
                    </form>

                    @if($targetUser && $targetUser->status_akun !== 'BANNED')
                        <!-- Peringatan Kuning -->
                        <form action="{{ route('admin.customer_service.warn', $komplain->id_komplain) }}" method="POST" class="mb-4 pb-4 border-bottom">
                            @csrf
                            <h6 class="font-weight-bold text-warning"><i class="bi bi-exclamation-triangle-fill"></i> Kirim Peringatan Kuning</h6>
                            <p class="small text-muted mb-2">Peringatan ini akan tampil di akun pengguna dan otomatis hilang dalam 5 hari.</p>
                            <input type="hidden" name="id_user_target" value="{{ $targetUser->id_user }}">
                            <textarea name="peringatan_teks" class="form-control mb-2" rows="2" placeholder="Alasan peringatan..." required></textarea>
                            <button type="submit" class="btn btn-warning w-100 font-weight-bold" onclick="return confirm('Kirim peringatan kuning kepada {{ $targetUser->nama }}?')">Kirim Peringatan</button>
                        </form>

                        <!-- Ban User -->
                        <form action="{{ route('admin.customer_service.ban', $komplain->id_komplain) }}" method="POST">
                            @csrf
                            <h6 class="font-weight-bold text-danger"><i class="bi bi-slash-circle-fill"></i> Blokir Akun (Banned)</h6>
                            <input type="hidden" name="id_user_target" value="{{ $targetUser->id_user }}">
                            <select name="banned_status" class="form-select mb-2" id="banned_status" onchange="toggleDatepicker()">
                                <option value="PERMANEN">PERMANEN</option>
                                <option value="SEMENTARA">SEMENTARA</option>
                            </select>
                            <div class="mb-2 d-none" id="date_picker_container">
                                <input type="datetime-local" name="tgl_berakhir" class="form-control">
                            </div>
                            <textarea name="alasan" class="form-control mb-2" rows="2" placeholder="Alasan blokir..." required></textarea>
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Yakin ingin memblokir akun ini?')">Blokir Pengguna</button>
                        </form>
                    @elseif($targetUser && $targetUser->status_akun === 'BANNED')
                        <div class="alert alert-danger text-center">
                            <strong>Pengguna ini sedang BANNED.</strong><br>
                            Harap buka blokir melalui menu Daftar Banned jika ingin memulihkan.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Profil Detail -->
@if($targetUser)
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="profileModalLabel">Detail Profil Pengguna</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-4">
            <img src="{{ $targetUser->profile_image ? asset('storage/'.$targetUser->profile_image) : asset('dist/assets/images/faces/1.jpg') }}" class="rounded-circle img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;" alt="Profile Image">
            <h5 class="mt-2 mb-0">{{ $targetUser->nama }}</h5>
            <span class="badge bg-secondary">{{ ucfirst($targetUser->role) }}</span>
        </div>
        <table class="table table-sm">
            <tr><th width="40%">ID User</th><td>{{ $targetUser->id_user }}</td></tr>
            <tr><th>Email</th><td>{{ $targetUser->email }}</td></tr>
            <tr><th>No. Telepon</th><td>{{ $targetUser->no_telepon ?? '-' }}</td></tr>
            <tr><th>Alamat</th><td>{{ $targetUser->alamat ?? '-' }}</td></tr>
            <tr><th>Status Akun</th><td>
                <span class="badge bg-{{ $targetUser->status_akun == 'BANNED' ? 'danger' : 'success' }}">{{ $targetUser->status_akun }}</span>
            </td></tr>
            <tr><th>Tanggal Daftar</th><td>{{ $targetUser->tanggal_daftar ? \Carbon\Carbon::parse($targetUser->tanggal_daftar)->format('d M Y') : '-' }}</td></tr>
            @if($targetUser->role == 'penjual' && $targetUser->penjual)
                <tr><th colspan="2" class="text-center bg-light mt-3">Detail Tambahan Penjual</th></tr>
                <tr><th>Status Verifikasi</th><td>{{ $targetUser->penjual->status_verifikasi ?? '-' }}</td></tr>
                <tr><th>Alamat Penjual</th><td>{{ $targetUser->penjual->alamatPJ ?? '-' }}</td></tr>
                <tr><th>Deskripsi Toko</th><td>{{ $targetUser->penjual->deskripsi_pj ?? '-' }}</td></tr>
            @endif
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endif

@endsection

@section('scripts')
<script>
    function toggleDatepicker() {
        const select = document.getElementById('banned_status');
        const dateContainer = document.getElementById('date_picker_container');
        if (select.value === 'SEMENTARA') {
            dateContainer.classList.remove('d-none');
            dateContainer.querySelector('input').setAttribute('required', 'true');
        } else {
            dateContainer.classList.add('d-none');
            dateContainer.querySelector('input').removeAttribute('required');
        }
    }
</script>
@endsection
