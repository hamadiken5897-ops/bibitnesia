<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdukController;

// Rute bawaan user (opsional, biarkan saja)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// --- Rute API Bibitnesia ---
Route::get('/produks', [ProdukController::class, 'index']);
Route::get('/produks/{id}', [ProdukController::class, 'show']);