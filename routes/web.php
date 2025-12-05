<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| CONTROLLER IMPORT
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\KomplainController;
use App\Http\Controllers\Admin\ValidasiController;

// Kurir Controllers
use App\Http\Controllers\Kurir\KurirInboxController;
use App\Http\Controllers\Kurir\KurirPengirimanController;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', [PortalController::class, 'index'])->name('portal');

// Login & Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (SETELAH LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTO ROLE DASHBOARD REDIRECT
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        return match (Auth::user()->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'penjual' => redirect()->route('penjual.dashboard'),
            'pembeli' => redirect()->route('pembeli.dashboard'),
            'kurir'   => redirect()->route('kurir.dashboard'),
            default => abort(403)
        };
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | PROFILE GLOBAL USER
    |--------------------------------------------------------------------------
    */
 Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show');
 Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');


    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

        // User Management
        Route::resource('/users', UserController::class)->except(['destroy']);
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');

        // Produk
        Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
        Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');

        // Pembayaran
        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
        Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');

        // Komplain
        Route::get('/komplain', [KomplainController::class, 'index'])->name('komplain');

        // Validasi
        Route::get('/validasi', [ValidasiController::class, 'index'])->name('validasi');
    });


    /*
    |--------------------------------------------------------------------------
    | PENJUAL ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('penjual')->name('penjual.')->group(function () {
        Route::get('/dashboard', fn() => view('penjual.dashboard'))->name('dashboard');
        // Tambahkan fitur penjual nanti
    });


    /*
    |--------------------------------------------------------------------------
    | PEMBELI ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('pembeli')->name('pembeli.')->group(function () {
        Route::get('/dashboard', fn() => view('pembeli.dashboard'))->name('dashboard');
        // Tambahkan fitur pembeli nanti
    });


    /*
    |--------------------------------------------------------------------------
    | KURIR ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('kurir')
    ->name('kurir.')
    ->middleware(['auth'])
    ->group(function () {
    Route::get('/dashboard', fn() => view('kurir.dashboard'))->name('dashboard');
    // PENGIRIMAN 
     Route::get('/pengiriman', [KurirPengirimanController::class, 'index'])->name('pengiriman');
     Route::get('/pengiriman/{id}', [KurirPengirimanController::class, 'show'])->name('pengiriman.detail');
     Route::post('/pengiriman/{id}/update-status', [KurirPengirimanController::class, 'updateStatus'])->name('pengiriman.update');
    // PEMBAYARAN (Sementara masih manual)
    Route::get('/pembayaran', fn() => view('kurir.pembayaran'))->name('pembayaran');
    // Profil Kurir
    Route::get('/profil', fn() => view('kurir.profil'))->name('profil');
    Route::put('/profil', function (Request $request) {

        $user = Auth::user();

        $request->validate([
                'nama' => 'required|string|max:100',
                'no_telepon' => 'nullable|string|max:20',
                'alamat' => 'nullable|string|max:255',
                'profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

        $user->update($request->only('nama','no_telepon','alamat'));

        return back()->with('success', 'Profile Kurir berhasil diperbarui');
        })->name('profil.update');

        // Inbox Kurir
        Route::get('/inbox', [KurirInboxController::class, 'index'])->name('inbox');
        Route::get('/inbox/{id}', [KurirInboxController::class, 'detail'])->name('inbox.detail');
        Route::post('/inbox/{id}/selesai', [KurirInboxController::class, 'selesai'])->name('inbox.selesai');
    });
});


/*
|--------------------------------------------------------------------------
| FILE HANDLER
|--------------------------------------------------------------------------
*/
Route::get('/files/{id}/{action}', function ($id, $action) {
    return \App\Models\File::findOrFail($id)->handleAction($action);
})->name('files.action');
