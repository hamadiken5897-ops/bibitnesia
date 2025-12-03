<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\KomplainController;
use App\Http\Controllers\Admin\ValidasiController;

// ========== ROUTE LOGIN DAN REGISTER ==========
Route::get('/', [AuthController::class, 'showLogin'])->name('login'); // halaman login utama
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// ✅ Rute ini wajib ada untuk forgot password
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========== ROUTE YANG BUTUH LOGIN ==========
Route::middleware(['auth'])->group(function () {
    // dashboard utama (akan diarahkan sesuai role)
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // ubah sesuai role nanti
    })->name('dashboard');

    // dashboard role admin
    Route::get('/admin/dashboard', function () {
        //dd(Auth::check(), Auth::user()); // ⬅ TEST 2
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // dashboard role penjual
    Route::get('/penjual/dashboard', function () {
        return view('penjual.dashboard');
    })->name('penjual.dashboard');

    // dashboard role pembeli
    Route::get('/pembeli/dashboard', function () {
        //dd(Auth::check(), Auth::user()); // ⬅ TEST 2
        return view('pembeli.dashboard');
    })->name('pembeli.dashboard');

    //=== Route Profile User === //
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show');

    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');

    //Route::get('/admin/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');

    //====== Dashboard Admin ======== //

    // ===== manajemen ====== //
    // rute manajemen user
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    // rute manajemen produk
    Route::get('/admin/produk', [ProdukController::class, 'index'])->name('admin.produk');
    Route::get('/admin/produk/create', [ProdukController::class, 'create'])->name('admin.produk.create');
    // rute manajemen pembayaran
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('admin.pembayaran');
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    // route search
    Route::get('/admin/users/search', [UserController::class, 'search'])->name('admin.users.search');

    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
            // MANAGEMEN USER (CRUD)
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
            Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

            // Komplain
            Route::get('/komplain', [KomplainController::class, 'index'])->name('komplain');

            // Validasi
            Route::get('/validasi', [ValidasiController::class, 'index'])->name('validasi');
        });
});

Route::get('/files/{id}/{action}', function ($id, $action) {
    $file = \App\Models\File::findOrFail($id);
    return $file->handleAction($action);
})->name('files.action');

// ========== ROUTE DASHBOARD USER ==========
Route::get('/user', function () {
    return redirect('/user/index.html');
});

Route::get('/user', function () {
    return view('/user/about.html');
});

Route::get('/user', function () {
    return view('/user/cart.html');
});
