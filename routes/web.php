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

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Petani Routes
Route::middleware(['auth', 'role:petani'])->prefix('petani')->name('petani.')->group(function () {
    Route::get('/dashboard', [PetaniController::class, 'dashboard'])->name('dashboard');
    Route::apiResource('tanaman', TanamanController::class);
    Route::apiResource('tanaman/{tanaman}/perawatan', PerawatanController::class);
    Route::apiResource('panen', PanenController::class);
    Route::apiResource('konsultasi', KonsultasiController::class);
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::post('/laporan', [LaporanController::class, 'store']);
});

// Ahli Routes
Route::middleware(['auth', 'role:ahli'])->prefix('ahli')->name('ahli.')->group(function () {
    Route::get('/dashboard', [AhliController::class, 'dashboard'])->name('dashboard');
    Route::get('/konsultasi', [KonsultasiController::class, 'index'])->name('konsultasi');
    Route::post('/konsultasi/{konsultasi}/jawab', [KonsultasiController::class, 'jawab'])->name('konsultasi.jawab');
    Route::get('/chat/{konsultasi}', [ChatController::class, 'konsultasi'])->name('chat');
    Route::post('/chat/{konsultasi}', [ChatController::class, 'sendKonsultasi']);
});

// Pedagang Routes
Route::middleware(['auth', 'role:pedagang'])->prefix('pedagang')->name('pedagang.')->group(function () {
    Route::get('/dashboard', [PedagangController::class, 'dashboard'])->name('dashboard');
    Route::get('/katalog', [PanenController::class, 'katalog'])->name('katalog');
    Route::apiResource('pesanan', PesananController::class);
    Route::get('/chat/{pesanan}', [ChatController::class, 'transaksi'])->name('chat');
    Route::post('/chat/{pesanan}', [ChatController::class, 'sendTransaksi']);
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::post('/laporan', [LaporanController::class, 'store']);
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/blokir', [AdminController::class, 'blokir'])->name('users.blokir');
    Route::post('/users/{user}/unblokir', [AdminController::class, 'unblokir'])->name('users.unblokir');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::post('/laporan/{laporan}/resolve', [LaporanController::class, 'resolve'])->name('laporan.resolve');
});