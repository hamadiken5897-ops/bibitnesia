<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\PembayaranController;

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
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'showProfile'])
        ->name('profile.show');

    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'updateProfile'])
        ->name('profile.update');

    //Route::get('/admin/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');

//====== Dashboard Admin ======== //
 // ===== manajemen ====== //
    // rute manajemen user
    Route::get('/admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])
        ->name('admin.users');
    // rute manajemen produk
    Route::get('/admin/produk', [App\Http\Controllers\Admin\ProdukController::class, 'index'])
        ->name('admin.produk');
    Route::get('/admin/produk/create', [ProdukController::class, 'create'])
        ->name('admin.produk.create');
    // rute manajemen pembayaran
    Route::get('/pembayaran', [PembayaranController::class, 'index'])
        ->name('admin.pembayaran');
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])
        ->name('pembayaran.show');

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


