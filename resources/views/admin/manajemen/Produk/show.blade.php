@extends('admin.layouts.app')

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
                                    {{-- Form ubah status langsung --}}
                                    <form id="status-form" 
                                          action="{{ route('admin.produk.update', $produk->id_produk) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" 
                                                class="form-select form-select-sm d-inline w-auto" 
                                                onchange="confirmStatusChange()">
                                            <option value="tersedia" {{ $produk->status == 'tersedia' ? 'selected' : '' }}>
                                                Tersedia
                                            </option>
                                            <option value="tidak_tersedia" {{ $produk->status == 'tidak_tersedia' ? 'selected' : '' }}>
                                                Tidak Tersedia
                                            </option>
                                            <option value="habis" {{ $produk->status == 'habis' ? 'selected' : '' }}>
                                                Habis
                                            </option>
                                            <option value="hidden" {{ $produk->status == 'hidden' ? 'selected' : '' }}>
                                                Hidden (Admin)
                                            </option>
                                        </select>
                                    </form>
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
                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                <i class="bi bi-trash"></i> Hapus Produk
                            </button>
                        </div>

                        <form id="delete-form" 
                              action="{{ route('admin.produk.destroy', $produk->id_produk) }}" 
                              method="POST" 
                              style="display: none;">
                            @csrf
                            @method('DELETE')
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
                                <td>{{ $produk->penjual->user->nama ?? '-' }}</td>
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
function confirmStatusChange() {
    if (confirm('Apakah Anda yakin ingin mengubah status produk ini?')) {
        document.getElementById('status-form').submit();
    } else {
        // Reset ke nilai sebelumnya
        location.reload();
    }
}

function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?\n\nTindakan ini tidak dapat dibatalkan!')) {
        document.getElementById('delete-form').submit();
    }
}

// Log untuk debugging
console.log('Produk ID:', '{{ $produk->id_produk }}');
console.log('Status Form Action:', document.getElementById('status-form')?.action);
console.log('Delete Form Action:', document.getElementById('delete-form')?.action);
</script>
@endsection