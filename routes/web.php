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
use App\Http\Controllers\CheckoutController;
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
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
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
| DASHBOARD PER ROLE
|--------------------------------------------------------------------------
*/

    // Dashboard routes akan dihandle di masing-masing prefix controller
    Route::get('/penjual/dashboard', [\App\Http\Controllers\Penjual\DashboardController::class, 'index'])->name('penjual.dashboard');
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

    // daftar penjual
    Route::get('/daftar-penjual', [PengajuanMitraController::class, 'index'])
        ->middleware('auth')
        ->name('daftar.penjual');

    // daftar kurir
    Route::get('/daftar-kurir', [PengajuanMitraController::class, 'index'])
        ->middleware('auth')
        ->name('daftar.kurir');

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

    /*
    |--------------------------------------------------------------------------
    | PENGATURAN AKUN (ACCOUNT SETTINGS)
    |--------------------------------------------------------------------------
    */
    Route::get('/account/profile', [App\Http\Controllers\AccountController::class, 'profile'])->name('account.profile');
    Route::get('/account/alamat', [App\Http\Controllers\AccountController::class, 'alamat'])->name('account.alamat');
    Route::post('/account/alamat', [App\Http\Controllers\AccountController::class, 'storeAlamat'])->name('account.alamat.store');
    Route::put('/account/alamat/{id}', [App\Http\Controllers\AccountController::class, 'updateAlamat'])->name('account.alamat.update');
    Route::post('/account/alamat/{id}/utama', [App\Http\Controllers\AccountController::class, 'setUtamaAlamat'])->name('account.alamat.utama');
    Route::delete('/account/alamat/{id}', [App\Http\Controllers\AccountController::class, 'deleteAlamat'])->name('account.alamat.delete');

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
    Route::post('/favorit/toggle', [FavoritController::class, 'toggle'])->name('favorit.toggle');
    Route::delete('/favorit/delete/{id}', [FavoritController::class, 'delete'])->name('favorit.delete');

    // riwayat marketplace
    Route::get('/riwayat', [RiwayatController::class, 'riwayat'])->name('riwayat');

    // ulasan
    Route::post('/ulasan/store', [App\Http\Controllers\UlasanController::class, 'store'])->name('ulasan.store');

    // pesan / chat
    Route::get('/pesan', [App\Http\Controllers\PesanController::class, 'index'])->name('pesan.index');
    Route::get('/pesan/unread', [App\Http\Controllers\PesanController::class, 'getUnreadCount'])->name('pesan.unread');
    Route::get('/pesan/tanya/{id_produk}', [App\Http\Controllers\PesanController::class, 'tanyaPenjual'])->name('pesan.tanya');
    Route::get('/pesan/{id}', [App\Http\Controllers\PesanController::class, 'show'])->name('pesan.show');
    Route::post('/pesan/store', [App\Http\Controllers\PesanController::class, 'store'])->name('pesan.store');

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

    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');

    // SIMPAN PESANAN (WAJIB LOGIN)
    Route::post('/checkout/store', [CheckoutController::class, 'store'])
        ->middleware('auth')
        ->name('checkout.store');

    Route::get('/marketplace/invoice/{id_pesanan}', [App\Http\Controllers\InvoiceController::class, 'show'])->name('marketplace.invoice');
    Route::get('/marketplace/pesanan-saya', [App\Http\Controllers\PesananController::class, 'index'])->name('marketplace.pesanan.saya');
    Route::post('/marketplace/pesanan-saya/{id}/selesai', [App\Http\Controllers\PesananController::class, 'selesai'])->name('marketplace.pesanan.selesai');
    Route::get('/pembayaran/{id}/proses', function ($id) {
        $pesanan = \App\Models\Pesanan::where('id_pesanan', $id)->firstOrFail();

        if ($pesanan->status_pesanan === 'Menunggu Pembayaran') {
            \Illuminate\Support\Facades\DB::transaction(function () use ($pesanan) {
                $pesanan->update([
                    'status_pesanan' => 'Pesanan sedang diproses',
                ]);

                \App\Models\Pembayaran::where('id_pesanan', $pesanan->id_pesanan)->update([
                    'status_validasi' => 'dibayar',
                    'tanggal_pembayaran' => now(),
                    'tgl_validasi' => now(),
                ]);
            });
        }

        return redirect()->route('marketplace.pesanan.saya')->with('success', 'Pembayaran berhasil dikonfirmasi (Simulasi Otomatis)');
    })->name('pembayaran.proses');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
            // ✅ DASHBOARD - Menggunakan AdminDashboardController
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            // USER
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
            Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
            Route::get('/users/search', [UserController::class, 'search'])->name('users.search');

            // PRODUK
            Route::prefix('produk')
                ->name('produk.')
                ->group(function () {
                    Route::get('/', [App\Http\Controllers\Admin\ProdukController::class, 'index'])->name('index');
                    Route::get('/{produk}', [App\Http\Controllers\Admin\ProdukController::class, 'show'])->name('show');
                    Route::get('/{produk}/edit', [App\Http\Controllers\Admin\ProdukController::class, 'edit'])->name('edit');
                    Route::put('/{produk}', [App\Http\Controllers\Admin\ProdukController::class, 'update'])->name('update');
                    Route::delete('/{produk}', [App\Http\Controllers\Admin\ProdukController::class, 'destroy'])->name('destroy');
                });
            // PEMBAYARAN
            Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
            Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');

            // KOMPLAIN
            Route::get('/komplain', [KomplainController::class, 'index'])->name('komplain');
            Route::put('/komplain/{id}/status', [KomplainController::class, 'updateStatus'])->name('komplain.status');
            Route::post('/komplain/{id}/ban', [KomplainController::class, 'banUser'])->name('komplain.ban');
            Route::post('/banned/{id_user}/unban', [KomplainController::class, 'unbanUser'])->name('banned.unban');

            // Pengajuan Mitra
            Route::get('/pengajuan-mitra', [PengajuanMitraController::class, 'adminIndex'])->name('pengajuan.index');
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
            // DASHBOARD
            Route::get('/dashboard', [PenjualController::class, 'index'])->name('dashboard');

            // SALDO
            Route::get('/saldo', [App\Http\Controllers\Penjual\SaldoController::class, 'index'])->name('saldo');

            // PRODUK
            Route::get('/produk', [App\Http\Controllers\Penjual\ProdukController::class, 'index'])->name('produk');
            Route::get('/produk/tambah', [App\Http\Controllers\Penjual\ProdukController::class, 'create'])->name('produk.create');
            Route::post('/produk', [App\Http\Controllers\Penjual\ProdukController::class, 'store'])->name('produk.store');
            Route::get('/produk/{produk}/edit', [App\Http\Controllers\Penjual\ProdukController::class, 'edit'])->name('produk.edit');
            Route::put('/produk/{produk}', [App\Http\Controllers\Penjual\ProdukController::class, 'update'])->name('produk.update');
            Route::delete('/produk/{produk}', [App\Http\Controllers\Penjual\ProdukController::class, 'destroy'])->name('produk.destroy');

            // PESANAN MASUK (PENJUAL)
            Route::get('/pesanan', [App\Http\Controllers\Penjual\PesananController::class, 'index'])->name('pesanan.index'); // ← Tambahkan .index

            Route::get('/pesanan/{id}', [App\Http\Controllers\Penjual\PesananController::class, 'show'])->name('pesanan.show');
            Route::post('/pesanan/{id}/accept', function ($id) {
                $pesanan = \App\Models\Pesanan::where('id_pesanan', $id)->firstOrFail();
                $pesanan->update([
                    'status_pesanan' => 'Pesanan dalam pengiriman',
                ]);
                return back()->with('success', 'Pesanan diterima');
            })->name('pesanan.accept');

            Route::post('/pesanan/{id}/reject', function (\Illuminate\Http\Request $request, $id) {
                $request->validate([
                    'alasan' => 'required|string',
                ]);

                $pesanan = \App\Models\Pesanan::where('id_pesanan', $id)->firstOrFail();
                $pesanan->update([
                    'status_pesanan' => 'Pesanan ditolak',
                    'catatan' => $request->alasan,
                ]);

                \App\Models\NotifikasiUser::create([
                    'id_user' => $pesanan->id_user,
                    'judul' => 'Pesanan Ditolak',
                    'pesan' => $request->alasan,
                    'type' => 'danger',
                ]);

                return back()->with('success', 'Pesanan ditolak');
            })->name('pesanan.reject');


            Route::get('/status-pesanan', [App\Http\Controllers\Penjual\StatusPesananController::class, 'index'])->name('status-pesanan.index');

            Route::get('/pesanan/{id}/kurir', [App\Http\Controllers\Penjual\PenjualPengirimanController::class, 'pilihKurir'])->name('pesanan.kurir');

            Route::post('/pesanan/{id}/kurir', [App\Http\Controllers\Penjual\PenjualPengirimanController::class, 'simpanKurir'])->name('pesanan.kurir.simpan');

            Route::get('/pengaturan', fn() => view('penjual.pengaturan'))->name('pengaturan');
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
            Route::get('/dashboard', [App\Http\Controllers\Kurir\KurirController::class, 'dashboard'])->name('dashboard');



            // 🚚 STATUS PENGIRIMAN
            Route::get('/status-pengiriman', [App\Http\Controllers\Kurir\PengirimanController::class, 'statusIndex'])->name('status-pengiriman.index');
            Route::get('/riwayat-pengiriman', [App\Http\Controllers\Kurir\PengirimanController::class, 'riwayat'])->name('riwayat-pengiriman.index');

       //     Route::get('/pengiriman/{id}/status', [App\Http\Controllers\Kurir\PengirimanController::class, 'status'])->name('pengiriman.status');

            Route::put('/pengiriman/{id}/status', [App\Http\Controllers\Kurir\PengirimanController::class, 'updateStatus'])->name('pengiriman.status.update');



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



            Route::get('/pengiriman', [App\Http\Controllers\Kurir\PengirimanController::class, 'index'])->name('pengiriman.index');

            Route::post('/pengiriman/{pengiriman}/terima', [App\Http\Controllers\Kurir\PengirimanController::class, 'accept'])->name('pengiriman.accept');

            Route::post('/pengiriman/{pengiriman}/selesai', [App\Http\Controllers\Kurir\PengirimanController::class, 'selesai'])->name('pengiriman.selesai');
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
