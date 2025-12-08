<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

<<<<<<< HEAD
use App\Http\Controllers\PortalController;
use App\Http\Controllers\MarketplaceController;

// Controller Auth
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
//pengajuan auth
use App\Http\Controllers\PengajuanMitraController;
=======
>>>>>>> 0f9ae956cdfc1d2493b1293d511b50c10a484095
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

<<<<<<< HEAD
=======
// Kurir Controllers
use App\Http\Controllers\Kurir\KurirInboxController;
use App\Http\Controllers\Kurir\KurirPengirimanController;


>>>>>>> 0f9ae956cdfc1d2493b1293d511b50c10a484095
/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

<<<<<<< HEAD
// Portal/Landing Page Route (root)

Route::get('/', function () {
    return view('portal');
})->name('portal');

Route::get('/', [PortalController::class, 'index'])->name('portal');

/*
|--------------------------------------------------------------------------
| Halaman LOGIN
|--------------------------------------------------------------------------
*/
// Halaman login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Proses login
=======
// Landing Page
Route::get('/', [PortalController::class, 'index'])->name('portal');

// Login & Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
>>>>>>> 0f9ae956cdfc1d2493b1293d511b50c10a484095
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Marketplace User Routes
Route::prefix('marketplace')
    ->name('marketplace.')
    ->group(function () {
        Route::get('/', [MarketplaceController::class, 'index'])->name('index');
        Route::get('/kategori/{kategori}', [MarketplaceController::class, 'kategori'])->name('kategori');
        Route::get('/produk/{id}', [MarketplaceController::class, 'show'])->name('show');
        Route::get('/search', [MarketplaceController::class, 'search'])->name('search');
    });

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
    | Daftar Pengajuan Kurir, Penjual
    |--------------------------------------------------------------------------
    */
    Route::post('/pengajuan-mitra/store', [PengajuanMitraController::class, 'store'])->name('pengajuan-mitra.store');

    // route daftar penjual
    Route::get('/daftar-penjual', function () {
        return view('mitra.daftar', ['role' => 'penjual']);
    })->name('daftar.penjual');

    // route daftar kurir
    Route::get('/daftar-kurir', function () {
        return view('mitra.daftar', ['role' => 'kurir']);
    })->name('daftar.kurir');

    Route::get('/pengajuan-mitra/success', function () {
        return view('mitra.succes');
    })->name('mitra.succes');

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
<<<<<<< HEAD
    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
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

            // Pengajuan Mitra
            Route::get('/pengajuan-mitra', [PengajuanMitraController::class, 'index'])->name('pengajuan.index');
            Route::get('/pengajuan-mitra/{id}', [PengajuanMitraController::class, 'show'])->name('pengajuan.show');

            Route::post('/pengajuan-mitra/{id}/approve', [PengajuanMitraController::class, 'approve'])->name('pengajuan.approve');
            Route::post('/pengajuan-mitra/{id}/reject', [PengajuanMitraController::class, 'reject'])->name('pengajuan.reject');
=======
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
>>>>>>> 0f9ae956cdfc1d2493b1293d511b50c10a484095

            Route::delete('/pengajuan-mitra/{id}', [PengajuanMitraController::class, 'destroy'])->name('pengajuan.destroy');
        });

    /*
    |--------------------------------------------------------------------------
    | PENJUAL ROUTES
    |--------------------------------------------------------------------------
    */
<<<<<<< HEAD
    Route::prefix('penjual')
        ->name('penjual.')
        ->group(function () {
            // masih kosong
        });
=======
    Route::prefix('penjual')->name('penjual.')->group(function () {
        Route::get('/dashboard', fn() => view('penjual.dashboard'))->name('dashboard');
        // Tambahkan fitur penjual nanti
    });

>>>>>>> 0f9ae956cdfc1d2493b1293d511b50c10a484095

    /*
    |--------------------------------------------------------------------------
    | PEMBELI ROUTES
    |--------------------------------------------------------------------------
    */
<<<<<<< HEAD
    Route::prefix('pembeli')
        ->name('pembeli.')
        ->group(function () {
            // masih kosong
        });
=======
    Route::prefix('pembeli')->name('pembeli.')->group(function () {
        Route::get('/dashboard', fn() => view('pembeli.dashboard'))->name('dashboard');
        // Tambahkan fitur pembeli nanti
    });

>>>>>>> 0f9ae956cdfc1d2493b1293d511b50c10a484095

    /*
    |--------------------------------------------------------------------------
    | KURIR ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('kurir')
<<<<<<< HEAD
        ->name('kurir.')
        ->group(function () {
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
                        'status' => 'Unread',
                    ],
                    2 => [
                        'pengirim' => 'Rina Putri',
                        'subjek' => 'Alamat Pengiriman',
                        'pesan' => 'Kak alamat saya sudah saya perbarui, mohon dicek ya.',
                        'tanggal' => '03 Jan 2025',
                        'status' => 'Read',
                    ],
                    3 => [
                        'pengirim' => 'Admin',
                        'subjek' => 'Pengiriman Gagal',
                        'pesan' => 'Mohon follow up pengiriman ID #SHIP-8891.',
                        'tanggal' => '02 Jan 2025',
                        'status' => 'Unread',
                    ],
                ];

                abort_if(!isset($messages[$id]), 404);

                return view('kurir.inbox-detail', [
                    'message' => $messages[$id],
                ]);
            })->name('inbox.detail');
        });
=======
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
>>>>>>> 0f9ae956cdfc1d2493b1293d511b50c10a484095
});

/*
|--------------------------------------------------------------------------
| FILE HANDLER
|--------------------------------------------------------------------------
*/
Route::get('/files/{id}/{action}', function ($id, $action) {
    return \App\Models\File::findOrFail($id)->handleAction($action);
})->name('files.action');
<<<<<<< HEAD

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
=======
>>>>>>> 0f9ae956cdfc1d2493b1293d511b50c10a484095
