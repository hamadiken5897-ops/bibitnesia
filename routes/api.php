<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdukController;

// Rute bawaan user (opsional, biarkan saja)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// --- Rute API Bibitnesia ---
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::get('/produks', [ProdukController::class, 'index']);
Route::get('/produks/{id}', [ProdukController::class, 'show']);
Route::get('/lokasi', [ProdukController::class, 'getLokasi']);

// Checkout & User Info (Wajib Login)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::post('/checkout', [\App\Http\Controllers\Api\CheckoutController::class, 'store']);
    
    // User Profile
    Route::get('/user/profile', [\App\Http\Controllers\Api\UserController::class, 'profile']);
    Route::put('/user/profile', [\App\Http\Controllers\Api\UserController::class, 'updateProfile']);
    Route::put('/user/password', [\App\Http\Controllers\Api\UserController::class, 'updatePassword']);
    
    // Favorit
    Route::get('/favorit', [\App\Http\Controllers\Api\UserController::class, 'getFavorites']);
    Route::post('/favorit/toggle', [\App\Http\Controllers\Api\UserController::class, 'toggleFavorite']);
    
    // Pesanan (Riwayat)
    Route::get('/pesanan', [\App\Http\Controllers\Api\UserController::class, 'getRiwayatPesanan']);
    Route::post('/pesanan/{id}/selesai', [\App\Http\Controllers\Api\UserController::class, 'selesaiPesanan']);
    Route::post('/pesanan/{id}/check-payment', [\App\Http\Controllers\Api\CheckoutController::class, 'checkPayment']);

    // Alamat
    Route::get('/provinsi', [\App\Http\Controllers\Api\UserController::class, 'getProvinsi']);
    Route::get('/alamat', [\App\Http\Controllers\Api\UserController::class, 'getAlamat']);
    Route::post('/alamat', [\App\Http\Controllers\Api\UserController::class, 'saveAlamat']);

    // Notifikasi
    Route::get('/notifikasi', [\App\Http\Controllers\Api\UserController::class, 'getNotifikasi']);
    Route::post('/notifikasi/read', [\App\Http\Controllers\Api\UserController::class, 'readNotifikasi']);
});