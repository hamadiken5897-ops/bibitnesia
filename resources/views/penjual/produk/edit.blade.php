@extends('layouts.penjual.penjual')

@section('page-title', 'Edit Produk')

@section('content')
    <div class="row g-4">

        {{-- PREVIEW --}}
        <div class="col-lg-6">
            @include('penjual.produk.partials.preview-edit')
        </div>

        {{-- FORM --}}
        <div class="col-lg-6">
            <form action="{{ route('penjual.produk.update', $produk->id_produk) }}" method="POST"
                enctype="multipart/form-data" id="editProdukForm" data-has-foto-utama="{{ $produk->foto_produk1 ? 1 : 0 }}">

                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}"
                        class="form-control" required>
                </div>

                {{-- Kategori --}}
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select" required>
                        <option value="Tanaman_hias" {{ $produk->kategori == 'Tanaman_hias' ? 'selected' : '' }}>
                            Tanaman Hias
                        </option>
                        <option value="sayur" {{ $produk->kategori == 'sayur' ? 'selected' : '' }}>
                            Sayur
                        </option>
                        <option value="buah" {{ $produk->kategori == 'buah' ? 'selected' : '' }}>
                            Buah
                        </option>
                    </select>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-control" required>{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                </div>

                {{-- Harga & Stok --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" value="{{ old('harga', $produk->harga) }}" class="form-control"
                            min="0" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" value="{{ old('stok', $produk->stok) }}" class="form-control"
                            min="0" required>
                    </div>
                </div>

                {{-- Foto --}}
                <div class="row mt-3">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Foto Utama</label>
                        <input type="file" name="foto_produk1" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Foto Tambahan</label>
                        <input type="file" name="foto_produk2" class="form-control">
                        <input type="hidden" name="remove_foto2" id="remove_foto2" value="0">

                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Foto Tambahan</label>
                        <input type="file" name="foto_produk3" class="form-control">
                        <input type="hidden" name="remove_foto3" id="remove_foto3" value="0">

                    </div>
                </div>

                {{-- ACTION --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('penjual.produk') }}" class="btn btn-secondary">
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-success">
                        Update Produk
                    </button>
                </div>

            </form>
        </div>

    </div>
@endsection
