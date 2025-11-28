@extends('layouts.admin.admin')

@section('title', 'Manajemen Produk')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Manajemen Produk</h3>
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

    {{-- Card Table --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>ID Produk</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produks as $index => $produk)
                            <tr>
                                <td>{{ $produks->firstItem() + $index }}</td>
                                <td>
                                    <img src="{{ $produk->foto_url }}" 
                                         alt="{{ $produk->nama_produk }}"
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                </td>
                                <td>{{ $produk->id_produk }}</td>
                                <td>{{ $produk->nama_produk }}</td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $produk->kategori)) }}</span>
                                </td>
                                <td>{{ $produk->formatted_harga }}</td>
                                <td>{{ $produk->stok }}</td>
                                <td>
                                    @if ($produk->status == 'tersedia')
                                        <span class="badge bg-success">Tersedia</span>
                                    @else
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.produk.show', $produk->id_produk) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.produk.edit', $produk->id_produk) }}" 
                                           class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.produk.destroy', $produk->id_produk) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    Belum ada produk
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $produks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection