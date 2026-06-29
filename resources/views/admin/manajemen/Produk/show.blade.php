@extends('layouts.admin.admin')

@section('title', 'Detail Produk')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Produk</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.produk.index') }}">Product Management</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            {{-- Foto Produk --}}
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        @if($produk->foto_produk1)
                            <img src="{{ asset('storage/' . $produk->foto_produk1) }}" 
                                 alt="{{ $produk->nama_produk }}"
                                 class="img-fluid rounded mb-3"
                                 onerror="this.src='https://via.placeholder.com/400x400?text=No+Image'">
                        @else
                            <img src="https://via.placeholder.com/400x400?text=No+Image" 
                                 alt="No Image"
                                 class="img-fluid rounded mb-3">
                        @endif
                        
                        <div class="row g-2">
                            @if($produk->foto_produk2)
                                <div class="col-6">
                                    <img src="{{ asset('storage/' . $produk->foto_produk2) }}" 
                                         class="img-fluid rounded"
                                         onerror="this.src='https://via.placeholder.com/200x200?text=No+Image'">
                                </div>
                            @endif
                            @if($produk->foto_produk3)
                                <div class="col-6">
                                    <img src="{{ asset('storage/' . $produk->foto_produk3) }}" 
                                         class="img-fluid rounded"
                                         onerror="this.src='https://via.placeholder.com/200x200?text=No+Image'">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Produk --}}
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h4>Informasi Produk</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="200">ID Produk</th>
                                <td>{{ $produk->id_produk }}</td>
                            </tr>
                            <tr>
                                <th>Nama Produk</th>
                                <td><strong>{{ $produk->nama_produk }}</strong></td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $produk->kategori)) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Harga</th>
                                <td class="text-success fw-bold">Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Stok</th>
                                <td>{{ $produk->stok }} unit</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($produk->status === 'hidden')
                                        <span class="badge bg-warning text-dark">Disembunyikan Admin</span>
                                    @elseif($produk->status === 'dihapus_admin')
                                        <span class="badge bg-danger">Dihapus Admin</span>
                                    @else
                                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $produk->status)) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>{{ $produk->deskripsi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Upload</th>
                                <td>{{ $produk->created_at ? $produk->created_at->format('d M Y, H:i') : '-' }}</td>
                            </tr>
                        </table>

                        <div class="mt-3">
                            <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            
                            @if($produk->status !== 'dihapus_admin')
                                @if($produk->status !== 'hidden')
                                    <button type="button" class="btn btn-warning text-dark mx-1" onclick="confirmHide()">
                                        <i class="bi bi-eye-slash"></i> Sembunyikan Produk
                                    </button>
                                @else
                                    <button type="button" class="btn btn-success mx-1" onclick="confirmUnhide()">
                                        <i class="bi bi-eye"></i> Tampilkan Produk
                                    </button>
                                @endif
                            @endif

                            @if(optional(auth()->user()->admin)->jabatan === 'super_admin')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                    <i class="bi bi-trash"></i> Hapus Produk
                                </button>
                            @endif
                        </div>

                        <form id="status-hide-form" action="{{ route('admin.produk.update', $produk->id_produk) }}" method="POST" style="display: none;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="hidden">
                            <input type="hidden" name="alasan_admin" id="hide-reason-input" value="">
                        </form>

                        <form id="status-unhide-form" action="{{ route('admin.produk.update', $produk->id_produk) }}" method="POST" style="display: none;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="tersedia">
                        </form>

                        <form id="delete-form" 
                              action="{{ route('admin.produk.destroy', $produk->id_produk) }}" 
                              method="POST" 
                              style="display: none;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="alasan_admin" id="delete-reason-input" value="">
                        </form>
                    </div>
                </div>

                {{-- Info Penjual --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Informasi Penjual</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="200">ID Penjual</th>
                                <td>{{ $produk->penjual->id_penjual ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>
                                    @if(isset($produk->penjual->user->id_user))
                                        <a href="{{ route('admin.users.show', $produk->penjual->user->id_user) }}" class="text-primary text-decoration-underline fw-bold">
                                            {{ $produk->penjual->user->nama ?? '-' }}
                                        </a>
                                    @else
                                        {{ $produk->penjual->user->nama ?? '-' }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $produk->penjual->user->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>No Telepon</th>
                                <td>{{ $produk->penjual->no_teleponPJ ?? $produk->penjual->user->no_telepon ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Provinsi</th>
                                <td>{{ $produk->penjual->provinsi->nama_provinsi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $produk->penjual->alamatPJ ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function confirmHide() {
    Swal.fire({
        title: 'Sembunyikan Produk?',
        text: "Berikan alasan mengapa produk ini disembunyikan (akan dikirim ke penjual):",
        icon: 'warning',
        input: 'textarea',
        inputPlaceholder: 'Tuliskan alasan Anda di sini...',
        inputAttributes: {
            'aria-label': 'Tuliskan alasan Anda di sini'
        },
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Sembunyikan',
        cancelButtonText: 'Batal',
        preConfirm: (reason) => {
            if (!reason) {
                Swal.showValidationMessage('Alasan harus diisi!')
            }
            return reason
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('hide-reason-input').value = result.value;
            document.getElementById('status-hide-form').submit();
        }
    });
}

function confirmUnhide() {
    Swal.fire({
        title: 'Tampilkan Produk?',
        text: "Apakah Anda yakin ingin menampilkan kembali produk ini ke marketplace?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Tampilkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('status-unhide-form').submit();
        }
    });
}

function confirmDelete() {
    Swal.fire({
        title: 'Hapus Produk?',
        text: "Berikan alasan mengapa produk ini dihapus/diblokir (akan dikirim ke penjual). Produk akan disembunyikan dan otomatis terhapus permanen setelah 7 hari:",
        icon: 'error',
        input: 'textarea',
        inputPlaceholder: 'Tuliskan alasan Anda di sini...',
        inputAttributes: {
            'aria-label': 'Tuliskan alasan Anda di sini'
        },
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus (Mulai 7 Hari)',
        cancelButtonText: 'Batal',
        preConfirm: (reason) => {
            if (!reason) {
                Swal.showValidationMessage('Alasan harus diisi!')
            }
            return reason
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-reason-input').value = result.value;
            document.getElementById('delete-form').submit();
        }
    });
}

// Log untuk debugging
console.log('Produk ID:', '{{ $produk->id_produk }}');
console.log('Status Form Action:', document.getElementById('status-form')?.action);
console.log('Delete Form Action:', document.getElementById('delete-form')?.action);
</script>
@endsection