<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
<<<<<<< HEAD

// ========== ROUTE LOGIN DAN REGISTER ==========
Route::get('/', [AuthController::class, 'showLogin'])->name('login'); // halaman login utama
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
=======
use App\Models\User;
// sementara buka dashboard tanpa auth (DEV only)
Route::get('/', function () {
    return view('admin.dashboard'); // atau view('dashboard') sesuai file kamu
})->name('dashboard');
// Route untuk admin dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

#Route::get('/', [AuthController::class, 'showLogin']);
>>>>>>> feb51423bde1c3d151801f866d0b903c1bbaaa05

// Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// Route::post('/login', [AuthController::class, 'login'])->name('login.post');

<<<<<<< HEAD
// ✅ Rute ini wajib ada untuk forgot password
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
=======
// Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
// Route::post('/register', [AuthController::class, 'register'])->name('register.post');
>>>>>>> feb51423bde1c3d151801f866d0b903c1bbaaa05

// Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
// Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

<<<<<<< HEAD
// ========== ROUTE YANG BUTUH LOGIN ==========
Route::middleware(['auth'])->group(function () {

    // dashboard utama (akan diarahkan sesuai role)
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // ubah sesuai role nanti
    })->name('dashboard');

    // dashboard per role (kalau kamu ingin beda)
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/penjual/dashboard', function () {
        return view('penjual.dashboard');
    })->name('penjual.dashboard');

    Route::get('/pembeli/dashboard', function () {
        return view('pembeli.dashboard');
    })->name('pembeli.dashboard');
});
=======
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('admin.dashboard'); // pastikan file-nya ada
//     })->name('dashboard');
// });
>>>>>>> feb51423bde1c3d151801f866d0b903c1bbaaa05
