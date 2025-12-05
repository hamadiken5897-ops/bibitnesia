<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\PortalController;

// Controller Auth
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| CONTROLLER ADMIN
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\KomplainController;
use App\Http\Controllers\Admin\ValidasiController;


/*
|--------------------------------------------------------------------------
| ROUTE LOGIN & AUTH
|--------------------------------------------------------------------------
*/

// Portal/Landing Page Route (root)
Route::get('/', function () {
    return view('portal');
})->name('portal');

Route::get('/', [PortalController::class, 'index'])->name('portal');

// Halaman login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');  

// Proses login
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Forgot Password
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| ROUTE SETELAH LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD PER ROLE
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    Route::get('/admin/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
    Route::get('/penjual/dashboard', fn() => view('penjual.dashboard'))->name('penjual.dashboard');
    Route::get('/pembeli/dashboard', fn() => view('pembeli.dashboard'))->name('pembeli.dashboard');
    Route::get('/kurir/dashboard', fn() => view('kurir.dashboard'))->name('kurir.dashboard');


    /*
    |--------------------------------------------------------------------------
    | PROFILE GLOBAL USER
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');


    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTE
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {

        // USER
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');

        // PRODUK
        Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
        Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');

        // PEMBAYARAN
        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
        Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');

        // KOMPLAIN
        Route::get('/komplain', [KomplainController::class, 'index'])->name('komplain');

        // VALIDASI
        Route::get('/validasi', [ValidasiController::class, 'index'])->name('validasi');
    });


    /*
    |--------------------------------------------------------------------------
    | PENJUAL ROUTE (KOSONG)
    |--------------------------------------------------------------------------
    */
    Route::prefix('penjual')->name('penjual.')->group(function () {
        // masih kosong
    });


    /*
    |--------------------------------------------------------------------------
    | PEMBELI ROUTE (KOSONG)
    |--------------------------------------------------------------------------
    */
    Route::prefix('pembeli')->name('pembeli.')->group(function () {
        // masih kosong
    });


    /*
    |--------------------------------------------------------------------------
    | KURIR ROUTE
    |--------------------------------------------------------------------------
    */
    Route::prefix('kurir')->name('kurir.')->group(function () {

        Route::get('/inbox', fn() => view('kurir.inbox'))->name('inbox');
        Route::get('/pengiriman', fn() => view('kurir.pengiriman'))->name('pengiriman');
        Route::get('/pembayaran', fn() => view('kurir.pembayaran'))->name('pembayaran');

        // PROFIL KURIR
        Route::get('/profil', fn() => view('kurir.profil'))->name('profil');

        Route::put('/profil', function (Request $request) {

            $user = Auth::user();

            $request->validate([
                'nama' => 'required|string|max:100',
                'no_telepon' => 'nullable|string|max:20',
                'alamat' => 'nullable|string|max:255',
                'profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $user->nama = $request->nama;
            $user->no_telepon = $request->no_telepon;
            $user->alamat = $request->alamat;
            $user->save();

            return back()->with('success', 'Profile Kurir berhasil diperbarui');
        })->name('profil.update');

        // INBOX KURIR
        Route::get('/inbox/{id}', function ($id) {

            // Contoh data dummy (karena belum pakai database inbox)
            $messages = [
                1 => [
                    'pengirim' => 'Admin BibitNesia',
                    'subjek' => 'Konfirmasi Pembayaran',
                    'pesan' => 'Mohon cek ulang pembayaran order dengan ID #INV-2025-01.',
                    'tanggal' => '03 Jan 2025',
                    'status' => 'Unread'
                ],
                2 => [
                    'pengirim' => 'Rina Putri',
                    'subjek' => 'Alamat Pengiriman',
                    'pesan' => 'Kak alamat saya sudah saya perbarui, mohon dicek ya.',
                    'tanggal' => '03 Jan 2025',
                    'status' => 'Read'
                ],
                3 => [
                    'pengirim' => 'Admin',
                    'subjek' => 'Pengiriman Gagal',
                    'pesan' => 'Mohon follow up pengiriman ID #SHIP-8891.',
                    'tanggal' => '02 Jan 2025',
                    'status' => 'Unread'
                ],
            ];

            abort_if(!isset($messages[$id]), 404);

            return view('kurir.inbox-detail', [
                'message' => $messages[$id]
            ]);

        })->name('inbox.detail');

    });
});


/*
|--------------------------------------------------------------------------
| FILE HANDLER
|--------------------------------------------------------------------------
*/
Route::get('/files/{id}/{action}', function ($id, $action) {
    $file = \App\Models\File::findOrFail($id);
    return $file->handleAction($action);
})->name('files.action');


/*
|--------------------------------------------------------------------------
| FRONTEND USER STATIC (DIRAPIKAN)
|--------------------------------------------------------------------------
*/
Route::prefix('user')->group(function () {
    Route::get('/', fn() => redirect('/user/index.html'));
    Route::get('/about', fn() => view('/user/about.html'));
    Route::get('/cart', fn() => view('/user/cart.html'));
});
