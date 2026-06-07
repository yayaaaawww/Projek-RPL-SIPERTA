<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetaniController;
use App\Http\Controllers\AhliController;
use App\Http\Controllers\PedagangController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TanamanController;
use App\Http\Controllers\PerawatanController;
use App\Http\Controllers\PanenController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LaporanController;

// Auth Routes (public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Petani
    Route::middleware('role:petani')->prefix('petani')->group(function () {
        Route::get('/dashboard', [PetaniController::class, 'dashboard']);
        Route::apiResource('tanaman', TanamanController::class);
        Route::apiResource('tanaman/{tanaman}/perawatan', PerawatanController::class);
        Route::apiResource('panen', PanenController::class);
        Route::apiResource('konsultasi', KonsultasiController::class);
        Route::get('/laporan', [LaporanController::class, 'index']);
        Route::post('/laporan', [LaporanController::class, 'store']);
    });

    // Ahli
    Route::middleware('role:ahli')->prefix('ahli')->group(function () {
        Route::get('/dashboard', [AhliController::class, 'dashboard']);
        Route::get('/konsultasi', [KonsultasiController::class, 'index']);
        Route::post('/konsultasi/{konsultasi}/jawab', [KonsultasiController::class, 'jawab']);
        Route::get('/chat/{konsultasi}', [ChatController::class, 'konsultasi']);
        Route::post('/chat/{konsultasi}', [ChatController::class, 'sendKonsultasi']);
    });

    // Pedagang
    Route::middleware('role:pedagang')->prefix('pedagang')->group(function () {
        Route::get('/dashboard', [PedagangController::class, 'dashboard']);
        Route::get('/katalog', [PanenController::class, 'katalog']);
        Route::apiResource('pesanan', PesananController::class);
        Route::get('/chat/{pesanan}', [ChatController::class, 'transaksi']);
        Route::post('/chat/{pesanan}', [ChatController::class, 'sendTransaksi']);
        Route::get('/laporan', [LaporanController::class, 'index']);
        Route::post('/laporan', [LaporanController::class, 'store']);
    });

    // Admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/users', [AdminController::class, 'users']);
        Route::post('/users/{user}/blokir', [AdminController::class, 'blokir']);
        Route::post('/users/{user}/unblokir', [AdminController::class, 'unblokir']);
        Route::get('/laporan', [LaporanController::class, 'index']);
        Route::post('/laporan/{laporan}/resolve', [LaporanController::class, 'resolve']);
    });

    // Petani konfirmasi pesanan
    Route::middleware('role:petani')->group(function () {
        Route::put('/pesanan/{pesanan}/konfirmasi', [PesananController::class, 'update']);
    });
});