<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Models\User;

// Dashboard route - jika BYPASS_AUTH=true maka auto-login user id 1, jika tidak pakai auth
Route::get('/dashboard', function () {
    if (env('BYPASS_AUTH', false)) {
        // pastikan user id 1 ada, kalau tidak return warning
        $user = \App\Models\User::find(1);
        if ($user) {
            Auth::loginUsingId($user->id);
            session()->regenerate();
        } else {
            return 'Dev bypass active but user id 1 not found.';
        }
    }

    return view('admin.dashboard');
})->middleware(env('BYPASS_AUTH', false) ? [] : ['auth'])->name('dashboard');
////////////////////////////////////////

Route::get('/', [AuthController::class, 'showLogin']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // pastikan file-nya ada
    })->name('dashboard');
});