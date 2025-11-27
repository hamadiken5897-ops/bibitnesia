@extends('layouts.admin')

@section('title', 'Profil Admin')
@section('page-title', 'Manajemen Pengguna')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Daftar Pengguna</h3>
                    <p class="text-subtitle text-muted">For user to check they list</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Daftar Pengguna</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Tambah akun --}}
        <div id="searchForm" class="d-flex mb-2">
            <button class="btn btn-primary ms-2" onclick="window.location='{{ route('admin.users.create') }}'">
                + Tambah User
            </button>
        </div>

        {{-- SEARCH BAR --}}
        <div id="searchForm" class="d-flex mb-2">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari nama / email ...">
            <button type="button" id="btnSearch" class="btn btn-primary ms-2">Cari</button>
        </div>

        {{-- FILTER DROPDOWN --}}
        <div id="filterForm" class="d-flex mb-3">
            <select id="sortSelect" class="form-select me-2">
                <option value="">Urutkan ID</option>
                <option value="asc">ID Terkecil → Terbesar</option>
                <option value="desc">ID Terbesar → Terkecil</option>
            </select>

            <select id="roleSelect" class="form-select me-2">
                <option value="">Semua Role</option>
                <option value="admin">Admin</option>
                <option value="pembeli">Pembeli</option>
                <option value="penjual">Penjual</option>
            </select>

            <select id="statusSelect" class="form-select me-2">
                <option value="">Status</option>
                <option value="admin">Aktif</option>
                <option value="pembeli">Nonaktif</option>
                <option value="penjual">Banned</option>
            </select>

            <button type="button" id="btnFilter" class="btn btn-secondary">Filter</button>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">Tabel Pengguna</div>
                <div id="table-container">
                    @include('admin.manajemen.User.table')
                </div>
            </div>
        </section>
    </div>
@endsection

{{-- ===================== JS AJAX ===================== --}}
@section('scripts')
    <script>
        "use strict";
        document.addEventListener("DOMContentLoaded", function() {

            const searchInput = document.getElementById('searchInput');
            const btnSearch = document.getElementById('btnSearch');
            const btnFilter = document.getElementById('btnFilter');
            const sortSelect = document.getElementById('sortSelect');
            const roleSelect = document.getElementById('roleSelect');
            const statusSelect = document.getElementById('statusSelect');
            const tableContainer = document.getElementById('table-container');

            function loadTable() {
                let url = "{{ route('admin.users.search') }}" +
                    "?search=" + encodeURIComponent(searchInput.value) +
                    "&sort=" + encodeURIComponent(sortSelect.value) +
                    "&role=" + encodeURIComponent(roleSelect.value) +
                    "&status=" + encodeURIComponent(statusSelect.value);

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        tableContainer.innerHTML = data.html;
                    })
                    .catch(err => {
                        console.error("Fetch error:", err);
                        alert("Terjadi error saat memuat data. Cek console.");
                    });
            }

            btnSearch.addEventListener("click", () => {
                try {
                    loadTable();
                } catch (e) {
                    console.error("Button error:", e);
                    alert("Error JS! Lihat console.");
                }
            });
            btn.addEventListener("click", () => {
                try {
                    loadTable();
                } catch (e) {
                    console.error("Button error:", e);
                    alert("Error JS! Lihat console.");
                }
            });
        });
    </script>
@endsection
