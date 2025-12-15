@extends('layouts.penjual.penjual')

@section('page-title', 'Tambah Produk')

@section('content')
    <div class="row align-items-stretch">
        {{-- PREVIEW --}}
        <div class="col-lg-6">
            <div class="h-100">
                @include('penjual.produk.partials.preview')
            </div>
        </div>
        {{-- Form --}}
        <div class="col-lg-6">
            <form id="produkForm" action="{{ route('penjual.produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Nama Produk --}}
                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror"
                        value="{{ old('nama_produk') }}" required>

                    @error('nama_produk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Tanaman_hias">Tanaman Hias</option>
                        <option value="sayur">Sayur</option>
                        <option value="buah">Buah</option>
                    </select>

                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" required>{{ old('deskripsi') }}</textarea>

                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Harga & Stok --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                            value="{{ old('harga') }}" required>

                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror"
                            value="{{ old('stok') }}" required>

                        @error('stok')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Foto Produk --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Foto Utama *</label>
                        <input type="file" name="foto_produk1"
                            class="form-control @error('foto_produk1') is-invalid @enderror" required>

                        @error('foto_produk1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Foto Tambahan</label>
                        <input type="file" name="foto_produk2" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Foto Tambahan</label>
                        <input type="file" name="foto_produk3" class="form-control">
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('penjual.produk') }}" class="btn btn-secondary" id="btnKembali">
                        Kembali
                    </a>


                    <button type="submit" class="btn btn-success px-4">
                        Simpan Produk
                    </button>
                </div>

            </form>

        </div>
    </div>

    </div>
    </div>
@endsection
