@extends('layouts.admin')
@section('title', 'Edit Produk')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Produk</h3>
                    <p class="text-subtitle text-muted">Update informasi produk</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.produk.index') }}">Produk</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                            <h4 class="card-title">Form Edit Produk</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.produk.update', $produk->id_produk) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Nama Produk --}}
                                <div class="form-group mb-3">
                                    <label for="nama_produk" class="form-label">Nama Produk <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_produk') is-invalid @enderror"
                                        id="nama_produk" name="nama_produk"
                                        value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                                    @error('nama_produk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Kategori --}}
                                <div class="form-group mb-3">
                                    <label for="kategori" class="form-label">Kategori <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('kategori') is-invalid @enderror" id="kategori"
                                        name="kategori" required>
                                        <option value="Tanaman_hias"
                                            {{ old('kategori', $produk->kategori) == 'Tanaman_hias' ? 'selected' : '' }}>
                                            Tanaman Hias</option>
                                        <option value="sayur"
                                            {{ old('kategori', $produk->kategori) == 'sayur' ? 'selected' : '' }}>Sayur
                                        </option>
                                        <option value="buah"
                                            {{ old('kategori', $produk->kategori) == 'buah' ? 'selected' : '' }}>Buah
                                        </option>
                                    </select>
                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Deskripsi --}}
                                <div class="form-group mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4"
                                        required>{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Row: Harga & Stok --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="harga" class="form-label">Harga (Rp) <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                                id="harga" name="harga" value="{{ old('harga', $produk->harga) }}"
                                                min="0" required>
                                            @error('harga')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="stok" class="form-label">Stok <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('stok') is-invalid @enderror"
                                                id="stok" name="stok" value="{{ old('stok', $produk->stok) }}"
                                                min="0" required>
                                            @error('stok')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="tersedia"
                                            {{ old('status', $produk->status) == 'tersedia' ? 'selected' : '' }}>Tersedia
                                        </option>
                                        <option value="habis"
                                            {{ old('status', $produk->status) == 'habis' ? 'selected' : '' }}>Habis
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Foto Lama --}}
                                @if ($produk->foto_produk)
                                    <div class="form-group mb-3">
                                        <label class="form-label">Foto Saat Ini</label>
                                        <div>
                                            <img src="{{ $produk->foto_url }}" alt="{{ $produk->nama_produk }}"
                                                style="max-width: 200px; border-radius: 8px;">
                                        </div>
                                    </div>
                                @endif

                                {{-- Upload Foto Baru --}}
                                <div class="form-group mb-3">
                                    <label for="foto_produk" class="form-label">Ganti Foto Produk</label>
                                    <input type="file" class="form-control @error('foto_produk') is-invalid @enderror"
                                        id="foto_produk" name="foto_produk" accept="image/*">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengganti foto</small>
                                    @error('foto_produk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Preview Foto Baru --}}
                                <div class="form-group mb-3">
                                    <img id="preview" src="#" alt="Preview"
                                        style="display:none; max-width: 200px; border-radius: 8px;">
                                </div>

                                {{-- Tombol --}}
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Update Produk
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            // Preview foto sebelum upload
            document.getElementById('foto_produk').addEventListener('change', function(e) {
                const preview = document.getElementById('preview');
                const file = e.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.style.display = 'none';
                }
            });
        </script>
    @endpush
@endsection
