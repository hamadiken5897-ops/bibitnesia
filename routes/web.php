<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\FavoritController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\NotifikasiController;

//pengajuan auth
use App\Http\Controllers\PengajuanMitraController;
/*
|--------------------------------------------------------------------------
| CONTROLLER IMPORT
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\User\ProfileGeneralController;

// Admin Controllers
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\KomplainController;
use App\Http\Controllers\Admin\ValidasiController;

// Kurir Controllers
use App\Http\Controllers\Kurir\KurirInboxController;
use App\Http\Controllers\Kurir\KurirPengirimanController;

//Penjual Controllers
use App\Http\Controllers\Penjual\PenjualController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
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
    /*
|--------------------------------------------------------------------------
| DASHBOARD PER ROLE (VERSI LAMA )
|--------------------------------------------------------------------------
*/

    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    Route::get('/admin/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
    Route::get('/penjual/dashboard', fn() => view('penjual.penjual'))->name('penjual.dashboard');
    Route::get('/pembeli/dashboard', fn() => view('pembeli.dashboard'))->name('pembeli.dashboard');
    Route::get('/kurir/dashboard', fn() => view('kurir.dashboard'))->name('kurir.dashboard');

    //notifikasi
    Route::post('/notifikasi/read/{id}', function ($id) {
        $n = \App\Models\NotifikasiUser::find($id);
        if ($n && auth()->check() && $n->id_user === auth()->user()->id_user) {
            $n->markAsRead();
            return response()->json(['ok' => true]);
        }
        return response()->json(['ok' => false], 403);
    })->middleware('auth');

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

    Route::get('/pengajuan-mitra/read/{id}', function ($id) {
        $p = \App\Models\PengajuanMitra::find($id);
        if ($p && $p->id_user == auth()->id()) {
            $p->is_read_user = true;
            $p->save();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | PROFILE GLOBAL USER
    |--------------------------------------------------------------------------
    */

    // profil global 
    // Lihat profil sendiri
    Route::get('/my-profile', [ProfileGeneralController::class, 'showOwn'])->name('profile.own');
    // Update profil
    Route::put('/my-profile', [ProfileGeneralController::class, 'update'])->name('profile.update.general');
    // Lihat profil user lain
    Route::get('/profil/{userId}', [ProfileGeneralController::class, 'show'])->name('profile.show');

    // profil admin
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profileA.show');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profileA.update');

    // Keranjang marketplace
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/add', [KeranjangController::class, 'add'])->name('keranjang.add');
    Route::post('/keranjang/update/{id}', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/delete/{id}', [KeranjangController::class, 'delete'])->name('keranjang.delete');

    // pesanan marketplace
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');

    // favorit marketplace
    Route::get('/favorit', [FavoritController::class, 'index'])->name('favorit.index');
    Route::post('/favorit/add', [FavoritController::class, 'add'])->name('favorit.add');
    Route::delete('/favorit/delete/{id}', [FavoritController::class, 'delete'])->name('favorit.delete');

    // riwayat marketplace
    Route::get('/riwayat', [RiwayatController::class, 'riwayat'])->name('riwayat');

    // riwayat notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');

    Route::post('/notifikasi/read-all', [NotifikasiController::class, 'readAll'])->name('notifikasi.readAll');

    Route::post('/notifikasi/delete/{id}', [NotifikasiController::class, 'delete'])->name('notifikasi.delete');

    Route::post('/notifikasi/delete-all', [NotifikasiController::class, 'deleteAll'])->name('notifikasi.deleteAll');
    // tampilan semua notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');

    Route::post('/notifikasi/read-all', [NotifikasiController::class, 'readAll'])->name('notifikasi.readAll');
    Route::post('/notifikasi/delete/{id}', [NotifikasiController::class, 'delete'])->name('notifikasi.delete');
    Route::post('/notifikasi/delete-all', [NotifikasiController::class, 'deleteAll'])->name('notifikasi.deleteAll');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
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

            Route::delete('/pengajuan-mitra/{id}', [PengajuanMitraController::class, 'destroy'])->name('pengajuan.destroy');
        });

    /*
    |--------------------------------------------------------------------------
    | PENJUAL ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('penjual')
        ->name('penjual.')
        ->group(function () {
            Route::get('/dashboard', function () {
                return view('penjual.penjual');
            })->name('dashboard');

            Route::get('/produk', function () {
                return view('penjual.produk');
            })->name('produk');

            Route::get('/produk/tambah', function () {
                return view('penjual.tambah-produk');
            })->name('produk.tambah');

            Route::get('/pesanan', function () {
                return view('penjual.pesanan');
            })->name('pesanan');

            Route::get('/pembayaran', function () {
                return view('penjual.pembayaran');
            })->name('pembayaran');

            Route::get('/pengturan', function () {
                return view('penjual.pengaturan');
            })->name('pengaturan');

            Route::get('/dashboard', [PenjualController::class, 'index'])->name('dashboard');

            Route::get('/penjual/pesanan', [PesananController::class, 'index'])->name('penjual.pesanan');
        });

    /*
    |--------------------------------------------------------------------------
    | PEMBELI ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('pembeli')
        ->name('pembeli.')
        ->group(function () {
            // masih kosong
        });

    /*
    |--------------------------------------------------------------------------
    | KURIR ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('kurir')
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
});

/*
|--------------------------------------------------------------------------
| FILE HANDLER
|--------------------------------------------------------------------------
*/
Route::get('/files/{id}/{action}', function ($id, $action) {
    return \App\Models\File::findOrFail($id)->handleAction($action);
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
