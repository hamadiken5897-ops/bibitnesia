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
});