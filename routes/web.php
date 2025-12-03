<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Controller Auth
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// Controller Admin
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\KomplainController;
use App\Http\Controllers\Admin\ValidasiController;

/*
|--------------------------------------------------------------------------
| ROUTE LOGIN & REGISTER
|--------------------------------------------------------------------------
*/

// Halaman login
Route::get('/', [AuthController::class, 'showLogin'])->name('login');

// Proses login
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Halaman register
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Proses register
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Forgot password
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');

// Proses kirim reset password
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

// Logout user
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



/*
|--------------------------------------------------------------------------
| ROUTE LOGIN (AUTH)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ALL ROLE
    |--------------------------------------------------------------------------
    */

    // Dashboard default
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // nanti diarahkan sesuai role
    })->name('dashboard');

    // Dashboard admin
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Dashboard penjual
    Route::get('/penjual/dashboard', function () {
        return view('penjual.dashboard');
    })->name('penjual.dashboard');

    // Dashboard pembeli
    Route::get('/pembeli/dashboard', function () {
        return view('pembeli.dashboard');
    })->name('pembeli.dashboard');

    // Dashboard kurir
    Route::get('/kurir/dashboard', function () {
        return view('kurir.dashboard');
    })->name('kurir.dashboard');



    /*
    |--------------------------------------------------------------------------
    | PROFILE USER
    |--------------------------------------------------------------------------
    */

    // Tampilan profile
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show');

    // Update profile
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');



    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTE (MANAGEMENT DATA)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {

        /*
        |-----------------------------------------
        | Manajemen User (CRUD)
        |-----------------------------------------
        */

        Route::get('/users', [UserController::class, 'index'])->name('users.index');     // list user
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create'); // form tambah user
        Route::post('/users', [UserController::class, 'store'])->name('users.store');   // simpan user baru
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit'); // edit user
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update'); // update user
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show'); // detail user
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search'); // search user


        /*
        |-----------------------------------------
        | Manajemen Produk
        |-----------------------------------------
        */

        Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');       // list produk
        Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create'); // tambah produk


        /*
        |-----------------------------------------
        | Manajemen Pembayaran
        |-----------------------------------------
        */

        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran'); // list pembayaran
        Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show'); // detail pembayaran


        /*
        |-----------------------------------------
        | Komplain
        |-----------------------------------------
        */

        Route::get('/komplain', [KomplainController::class, 'index'])->name('komplain');


        /*
        |-----------------------------------------
        | Validasi
        |-----------------------------------------
        */

        Route::get('/validasi', [ValidasiController::class, 'index'])->name('validasi');
    });



    /*
    |--------------------------------------------------------------------------
    | PENJUAL ROUTE (MASIH KOSONG SESUAI KODE ASLI)
    |--------------------------------------------------------------------------
    */
    Route::prefix('penjual')->name('penjual.')->group(function () {
        // tetap kosong sesuai kode Anda
    });



    /*
    |--------------------------------------------------------------------------
    | PEMBELI ROUTE (MASIH KOSONG SESUAI KODE ASLI)
    |--------------------------------------------------------------------------
    */
    Route::prefix('pembeli')->name('pembeli.')->group(function () {
        // tetap kosong sesuai kode Anda
    });



    /*
    |--------------------------------------------------------------------------
    | KURIR ROUTE (MASIH KOSONG SESUAI KODE ASLI)
    |--------------------------------------------------------------------------
    */
    Route::prefix('kurir')->name('kurir.')->group(function () {

            // Inbox Kurir
            Route::get('/inbox', function () {
                return view('kurir.inbox');
            })->name('inbox');  // menghasilkan route: kurir.inbox

            // Pengiriman Kurir
            Route::get('/pengiriman', function () {
                return view('kurir.pengiriman');
            })->name('pengiriman'); // menghasilkan route: kurir.pengiriman

            // Pembayaran Kurir
            Route::get('/pembayaran', function () {
                return view('kurir.pembayaran');
            })->name('pembayaran'); // menghasilkan route: kurir.pembayaran

        });
});



/*
|--------------------------------------------------------------------------
| ROUTE FILE HANDLER
|--------------------------------------------------------------------------
*/
Route::get('/files/{id}/{action}', function ($id, $action) {
    $file = \App\Models\File::findOrFail($id);
    return $file->handleAction($action);
})->name('files.action');



/*
|--------------------------------------------------------------------------
| ROUTE FRONTEND USER STATIC
|--------------------------------------------------------------------------
*/

Route::get('/user', function () {
    return redirect('/user/index.html');
});

Route::get('/user', function () {
    return view('/user/about.html');
});

Route::get('/user', function () {
    return view('/user/cart.html');
});
