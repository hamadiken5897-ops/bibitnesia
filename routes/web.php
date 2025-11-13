<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
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

// Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
// Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
// Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('admin.dashboard'); // pastikan file-nya ada
//     })->name('dashboard');
// });