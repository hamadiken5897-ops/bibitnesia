@extends('account.layout')

@section('account_content')
<div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
    <h4 class="fw-bold mb-0">Alamat Saya</h4>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahAlamatModal">
        <i class="fas fa-plus"></i> Tambah Alamat Baru
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="alamat-list">
    @forelse($alamats as $alamat)
        <div class="alamat-item border-bottom py-3 {{ $alamat->is_utama ? 'bg-light rounded p-3' : '' }}">
            <div class="row">
                <div class="col-md-9">
                    <div class="d-flex align-items-center mb-1">
                        <strong class="me-2 fs-5">{{ $alamat->nama_penerima }}</strong>
                        <span class="text-muted border-start ps-2">({{ $alamat->no_telepon }})</span>
                    </div>
                    <p class="mb-1 text-muted">{{ $alamat->detail_alamat }}</p>
                    <p class="mb-2 text-muted text-uppercase">{{ $alamat->kecamatan ? $alamat->kecamatan . ', ' : '' }}{{ $alamat->kota }}, {{ $alamat->provinsi->nama_provinsi }}, {{ $alamat->kode_pos }}</p>
                    
                    @if($alamat->is_utama)
                        <span class="badge border border-success text-success bg-white px-2 py-1">Utama</span>
                    @endif
                </div>
                <div class="col-md-3 text-md-end d-flex flex-column justify-content-between align-items-md-end mt-3 mt-md-0">
                    <div class="mb-2">
                        <button type="button" class="btn btn-link text-decoration-none p-0 me-3" data-bs-toggle="modal" data-bs-target="#editAlamatModal{{ $alamat->id }}">Ubah</button>
                        
                        @if(!$alamat->is_utama)
                            <form action="{{ route('account.alamat.delete', $alamat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus alamat ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger text-decoration-none p-0">Hapus</button>
                            </form>
                        @endif
                    </div>
                    
                    @if(!$alamat->is_utama)
                        <form action="{{ route('account.alamat.utama', $alamat->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm">Atur sebagai utama</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editAlamatModal{{ $alamat->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title fw-bold">Ubah Alamat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('account.alamat.update', $alamat->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body py-0">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <input type="text" name="nama_penerima" class="form-control" placeholder="Nama Lengkap" value="{{ $alamat->nama_penerima }}" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <input type="text" name="no_telepon" class="form-control" placeholder="Nomor Telepon" value="{{ $alamat->no_telepon }}" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <select name="id_provinsi" class="form-select" required>
                                    <option value="">Pilih Provinsi</option>
                                    @foreach($provinsis as $p)
                                        <option value="{{ $p->id_provinsi }}" {{ $alamat->id_provinsi == $p->id_provinsi ? 'selected' : '' }}>{{ $p->nama_provinsi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <input type="text" name="kota" class="form-control" placeholder="Kota/Kabupaten" value="{{ $alamat->kota }}" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <input type="text" name="kecamatan" class="form-control" placeholder="Kecamatan" value="{{ $alamat->kecamatan }}">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <input type="text" name="kode_pos" class="form-control" placeholder="Kode Pos" value="{{ $alamat->kode_pos }}">
                            </div>
                            
                            <div class="mb-3">
                                <textarea name="detail_alamat" class="form-control" rows="3" placeholder="Nama Jalan, Gedung, No. Rumah" required>{{ $alamat->detail_alamat }}</textarea>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="is_utama" value="1" id="is_utama_edit_{{ $alamat->id }}" {{ $alamat->is_utama ? 'checked disabled' : '' }}>
                                <label class="form-check-label text-muted" for="is_utama_edit_{{ $alamat->id }}">
                                    Jadikan Alamat Utama
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 justify-content-between">
                            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Nanti Saja</button>
                            <button type="submit" class="btn btn-success px-4">OK</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5 text-muted">
            <i class="fas fa-map-marked-alt fa-3x mb-3 text-light"></i>
            <p>Anda belum memiliki alamat tersimpan.</p>
        </div>
    @endforelse
</div>

<!-- Tambah Modal -->
<div class="modal fade" id="tambahAlamatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">Alamat Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('account.alamat.store') }}" method="POST">
                @csrf
                <div class="modal-body py-0">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <input type="text" name="nama_penerima" class="form-control" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="col-6 mb-3">
                            <input type="text" name="no_telepon" class="form-control" placeholder="Nomor Telepon" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <select name="id_provinsi" class="form-select" required>
                            <option value="">Pilih Provinsi</option>
                            @foreach($provinsis as $p)
                                <option value="{{ $p->id_provinsi }}">{{ $p->nama_provinsi }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-3">
                            <input type="text" name="kota" class="form-control" placeholder="Kota/Kabupaten" required>
                        </div>
                        <div class="col-6 mb-3">
                            <input type="text" name="kecamatan" class="form-control" placeholder="Kecamatan">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <input type="text" name="kode_pos" class="form-control" placeholder="Kode Pos">
                    </div>
                    
                    <div class="mb-3">
                        <textarea name="detail_alamat" class="form-control" rows="3" placeholder="Nama Jalan, Gedung, No. Rumah" required></textarea>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_utama" value="1" id="is_utama_add">
                        <label class="form-check-label text-muted" for="is_utama_add">
                            Jadikan Alamat Utama
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Nanti Saja</button>
                    <button type="submit" class="btn btn-success px-4">OK</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
