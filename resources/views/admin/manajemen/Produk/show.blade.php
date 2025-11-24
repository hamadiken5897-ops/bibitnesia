@extends('layouts.admin')
@section('title', 'Detail Produk')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Detail Produk</h3>
                    <p class="text-subtitle text-muted">Informasi lengkap produk</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.produk.index') }}">Produk</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ $produk->nama_produk }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- Foto Produk --}}
                                <div class="col-md-5 mb-4">
                                    <img src="{{ $produk->foto_url }}" alt="{{ $produk->nama_produk }}"
                                        class="img-fluid rounded" style="width: 100%; object-fit: cover;">
                                </div>

                                {{-- Informasi Produk --}}
                                <div class="col-md-7">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="35%">ID Produk</th>
                                            <td>: {{ $produk->id_produk }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Produk</th>
                                            <td>: {{ $produk->nama_produk }}</td>
                                        </tr>
                                        <tr>
                                            <th>Kategori</th>
                                            <td>:
                                                @if ($produk->kategori === 'Tanaman_hias')
                                                    <span class="badge bg-info">Tanaman Hias</span>
                                                @elseif ($produk->kategori === 'sayur')
                                                    <span class="badge bg-success">Sayur</span>
                                                @else
                                                    <span class="badge bg-warning">Buah</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Harga</th>
                                            <td>: <strong class="text-primary">{{ $produk->formatted_harga }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Stok</th>
                                            <td>: <strong>{{ $produk->stok }}</strong> unit</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>:
                                                @if ($produk->status === 'tersedia')
                                                    <span class="badge bg-success">Tersedia</span>
                                                @else
                                                    <span class="badge bg-danger">Habis</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Penjual</th>
                                            <td>: {{ $produk->penjual->nama ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ditambahkan</th>
                                            <td>: {{ $produk->created_at->format('d M Y, H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Terakhir Update</th>
                                            <td>: {{ $produk->updated_at->format('d M Y, H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="mt-3">
                                <h5>Deskripsi Produk</h5>
                                <p class="text-muted">{{ $produk->deskripsi }}</p>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                <div>
                                    <a href="{{ route('admin.produk.edit', $produk->id_produk) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.produk.destroy', $produk->id_produk) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
