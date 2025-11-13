<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
