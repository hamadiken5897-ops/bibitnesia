@extends('layouts.admin.admin')

@section('title', 'Manajemen Pembayaran')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Manajemen Pembayaran</h3>
    </div>
</div>

<div class="page-content">

    {{-- Alert --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Card with Tabs --}}
    <div class="card">
        <div class="card-header pb-0">
            <ul class="nav nav-tabs" id="paymentTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="keuangan-tab" data-bs-toggle="tab" data-bs-target="#keuangan" type="button" role="tab" aria-controls="keuangan" aria-selected="true">
                        <i class="bi bi-pie-chart-fill me-2"></i>Ringkasan Keuangan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="masuk-tab" data-bs-toggle="tab" data-bs-target="#masuk" type="button" role="tab" aria-controls="masuk" aria-selected="false">
                        <i class="bi bi-box-arrow-in-down-right me-2"></i>Uang Masuk (Customer)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="keluar-tab" data-bs-toggle="tab" data-bs-target="#keluar" type="button" role="tab" aria-controls="keluar" aria-selected="false">
                        <i class="bi bi-box-arrow-up-right me-2"></i>Uang Keluar (Payouts Mitra)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="keluar-pembeli-tab" data-bs-toggle="tab" data-bs-target="#keluar-pembeli" type="button" role="tab" aria-controls="keluar-pembeli" aria-selected="false">
                        <i class="bi bi-people me-2"></i>Uang Keluar (Payouts Customer)
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body mt-3">
            <div class="tab-content" id="paymentTabContent">

                {{-- TAB: RINGKASAN KEUANGAN --}}
                <div class="tab-pane fade show active" id="keuangan" role="tabpanel" aria-labelledby="keuangan-tab">
                    
                    {{-- Financial Cards --}}
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white;">
                                <div class="card-body py-4">
                                    <h6 class="text-white-50 text-uppercase fw-bold mb-2">Total Perputaran Uang (GTV)</h6>
                                    <h3 class="mb-0 text-white">Rp {{ number_format($totalUangMasuk, 0, ',', '.') }}</h3>
                                    <small class="text-white-50 mt-2 d-block">Keseluruhan uang dari pembeli</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); color: #333;">
                                <div class="card-body py-4">
                                    <h6 class="text-dark-50 text-uppercase fw-bold mb-2">Saldo Mitra Tertahan (Escrow)</h6>
                                    <h3 class="mb-0 text-dark">Rp {{ number_format($saldoTertahan, 0, ',', '.') }}</h3>
                                    <small class="text-dark-50 mt-2 d-block">Hak penjual & kurir (Belum ditarik)</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); color: white;">
                                <div class="card-body py-4">
                                    <h6 class="text-white-50 text-uppercase fw-bold mb-2">Pendapatan Bersih (Profit)</h6>
                                    <h3 class="mb-0 text-white">Rp {{ number_format($totalKomisi, 0, ',', '.') }}</h3>
                                    <small class="text-white-50 mt-2 d-block">Total akumulasi komisi {{ floatval($komisiPersen) }}% platform BibitNesia</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Data Saldo Penjual & Kurir --}}
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="bi bi-shop me-2"></i>Saldo Penjual</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID Penjual</th>
                                            <th>Nama Toko</th>
                                            <th class="text-end">Saldo Aktif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($penjuals as $penjual)
                                            <tr>
                                                <td>{{ $penjual->id_penjual }}</td>
                                                <td>{{ $penjual->nama_penjual }}</td>
                                                <td class="text-end fw-bold text-success">Rp {{ number_format($penjual->saldo, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Belum ada penjual yang memiliki saldo.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="bi bi-truck me-2"></i>Saldo Kurir</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID Kurir</th>
                                            <th>Area</th>
                                            <th class="text-end">Saldo Aktif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kurirs as $kurir)
                                            <tr>
                                                <td>{{ $kurir->id_kurir }}</td>
                                                <td>{{ $kurir->daerah }}</td>
                                                <td class="text-end fw-bold text-success">Rp {{ number_format($kurir->saldo, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Belum ada kurir yang memiliki saldo.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Data Saldo Pembeli --}}
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="mb-3"><i class="bi bi-people me-2"></i>Saldo Pembeli (Customer)</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID User</th>
                                            <th>Nama Pembeli</th>
                                            <th>Email</th>
                                            <th class="text-end">Saldo Aktif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pembelis as $p)
                                            <tr>
                                                <td>{{ $p->id_user }}</td>
                                                <td>{{ $p->nama }}</td>
                                                <td>{{ $p->email }}</td>
                                                <td class="text-end fw-bold text-success">Rp {{ number_format($p->saldo, 0, ',', '.') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Belum ada pembeli yang memiliki saldo.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                
                {{-- TAB: UANG MASUK --}}
                <div class="tab-pane fade" id="masuk" role="tabpanel" aria-labelledby="masuk-tab">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ID Pembayaran</th>
                                    <th>ID Pesanan</th>
                                    <th>Metode</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pembayarans as $index => $p)
                                    <tr>
                                        <td>{{ $pembayarans->firstItem() + $index }}</td>
                                        <td>{{ $p->id_pembayaran }}</td>
                                        <td>{{ $p->id_pesanan }}</td>
                                        <td>{{ ucfirst($p->metode_pembayaran) }}</td>
                                        <td>Rp {{ number_format($p->total_bayar, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($p->status_validasi === 'paid' || strtolower($p->status_validasi ?? '') === 'valid' || $p->status_validasi === 'sudah_bayar' || $p->status_validasi === 'dibayar')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif ($p->status_validasi === 'pending' || $p->status_validasi === 'belum_bayar')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Failed</span>
                                            @endif
                                        </td>
                                        <td>{{ $p->tanggal_pembayaran ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('admin.pembayaran.show', $p->id_pembayaran) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            Belum ada data pembayaran masuk.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $pembayarans->links() }}
                    </div>
                </div>

                {{-- TAB: UANG KELUAR (PAYOUTS) MITRA --}}
                <div class="tab-pane fade" id="keluar" role="tabpanel" aria-labelledby="keluar-tab">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mitra</th>
                                    <th>Role</th>
                                    <th>Bank & Rekening</th>
                                    <th>Jumlah (Rp)</th>
                                    <th>Status</th>
                                    <th>Tgl Pengajuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($penarikanMitras ?? [] as $index => $tarik)
                                    <tr>
                                        <td>{{ $penarikanMitras->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $tarik->user_id }}</strong><br>
                                            <small class="text-muted">{{ $tarik->nama_pemilik_rekening }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $tarik->role === 'penjual' ? 'primary' : 'secondary' }}">{{ ucfirst($tarik->role) }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ strtoupper($tarik->nama_bank) }}</strong><br>
                                            {{ $tarik->no_rekening }}
                                        </td>
                                        <td class="text-danger font-bold">
                                            - Rp {{ number_format($tarik->jumlah_penarikan, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if ($tarik->status === 'selesai')
                                                <span class="badge bg-success">Selesai</span>
                                            @elseif ($tarik->status === 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif ($tarik->status === 'diproses')
                                                <span class="badge bg-info">Diproses</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>{{ $tarik->tgl_pengajuan->format('d-M-Y H:i') }}</td>
                                        <td>
                                            @if (in_array($tarik->status, ['pending', 'diproses']))
                                                <button class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalProses{{ $tarik->id_penarikan }}">
                                                    Proses
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    Selesai
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            Belum ada permintaan penarikan saldo (payouts) dari Mitra.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        @if(isset($penarikanMitras))
                            {{ $penarikanMitras->links() }}
                        @endif
                    </div>
                </div>

                {{-- TAB: UANG KELUAR (PAYOUTS) PEMBELI --}}
                <div class="tab-pane fade" id="keluar-pembeli" role="tabpanel" aria-labelledby="keluar-pembeli-tab">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Pembeli (Customer)</th>
                                    <th>Role</th>
                                    <th>Bank & Rekening</th>
                                    <th>Jumlah (Rp)</th>
                                    <th>Status</th>
                                    <th>Tgl Pengajuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($penarikanPembelis ?? [] as $index => $tarik)
                                    <tr>
                                        <td>{{ $penarikanPembelis->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $tarik->user_id }}</strong><br>
                                            <small class="text-muted">{{ $tarik->nama_pemilik_rekening }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ ucfirst($tarik->role) }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ strtoupper($tarik->nama_bank) }}</strong><br>
                                            {{ $tarik->no_rekening }}
                                        </td>
                                        <td class="text-danger font-bold">
                                            - Rp {{ number_format($tarik->jumlah_penarikan, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if ($tarik->status === 'selesai')
                                                <span class="badge bg-success">Selesai</span>
                                            @elseif ($tarik->status === 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif ($tarik->status === 'diproses')
                                                <span class="badge bg-info">Diproses</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>{{ $tarik->tgl_pengajuan->format('d-M-Y H:i') }}</td>
                                        <td>
                                            @if (in_array($tarik->status, ['pending', 'diproses']))
                                                <button class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalProses{{ $tarik->id_penarikan }}">
                                                    Proses
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    Selesai
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            Belum ada permintaan penarikan saldo (payouts) dari Pembeli.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        @if(isset($penarikanPembelis))
                            {{ $penarikanPembelis->links() }}
                        @endif
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

{{-- MODAL PROSES PENARIKAN SALDO --}}
@php
    $allPayouts = collect();
    if(isset($penarikanMitras)) $allPayouts = $allPayouts->merge($penarikanMitras->items());
    if(isset($penarikanPembelis)) $allPayouts = $allPayouts->merge($penarikanPembelis->items());
@endphp
@foreach($allPayouts as $tarik)
    @if (in_array($tarik->status, ['pending', 'diproses']))
    <div class="modal fade" id="modalProses{{ $tarik->id_penarikan }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Proses Penarikan Saldo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.pembayaran.payout.update', $tarik->id_penarikan) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info mb-4">
                            <h6 class="alert-heading fw-bold mb-1">Panduan Admin:</h6>
                            <p class="mb-0 small">Silakan transfer manual uang sebesar <strong>Rp {{ number_format($tarik->jumlah_penarikan, 0, ',', '.') }}</strong> ke rekening di bawah ini, lalu ubah statusnya menjadi "Selesai". Pengguna akan otomatis menerima Notifikasi Lonceng.</p>
                        </div>
                        
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="35%" class="text-muted">Nama Pengguna</td>
                                <td class="fw-bold">: {{ $tarik->user_id }} ({{ ucfirst($tarik->role) }})</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Bank Tujuan</td>
                                <td class="fw-bold">: {{ strtoupper($tarik->nama_bank) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">No. Rekening</td>
                                <td class="fw-bold text-primary">: {{ $tarik->no_rekening }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Atas Nama</td>
                                <td class="fw-bold">: {{ $tarik->nama_pemilik_rekening }}</td>
                            </tr>
                        </table>

                        <hr>

                        <div class="form-group mb-3">
                            <label class="fw-bold mb-2">Ubah Status</label>
                            <select name="status" class="form-select" required onchange="toggleAlasan(this, {{ $tarik->id_penarikan }})">
                                <option value="" disabled selected>Pilih Aksi...</option>
                                <option value="selesai">Selesai (Sudah Ditransfer)</option>
                                <option value="ditolak">Tolak & Kembalikan Saldo</option>
                            </select>
                        </div>

                        <div class="form-group d-none" id="alasanTolak{{ $tarik->id_penarikan }}">
                            <label class="fw-bold mb-2 text-danger">Alasan Penolakan</label>
                            <textarea name="alasan_penolakan" class="form-control" rows="2" placeholder="Contoh: Nomor Rekening tidak valid..."></textarea>
                            <small class="text-muted">Pesan ini akan dikirim ke notifikasi pengguna.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-bold">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

<script>
function toggleAlasan(select, id) {
    const textarea = document.getElementById('alasanTolak' + id);
    if (select.value === 'ditolak') {
        textarea.classList.remove('d-none');
        textarea.querySelector('textarea').required = true;
    } else {
        textarea.classList.add('d-none');
        textarea.querySelector('textarea').required = false;
    }
}
</script>

@endsection

