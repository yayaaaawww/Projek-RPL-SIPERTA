<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetaniController;
use App\Http\Controllers\AhliController;
use App\Http\Controllers\PedagangController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes (versi Blade / session auth)
|--------------------------------------------------------------------------
| TAHAP 1: auth + dashboard tiap role.
| Halaman dalam (data, katalog, konsultasi, dll) ditambahkan bertahap.
*/

// Halaman depan: kalau sudah login -> dashboard sesuai role, kalau belum -> login
Route::get('/', function () {
    $user = auth()->user();
    if (! $user) {
        return redirect()->route('login');
    }
    return redirect()->route($user->role . '.dashboard');
})->name('home');

// --- Tamu (belum login) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// --- Sudah login ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Room chat konsultasi (diakses petani & ahli terkait)
    Route::get('/konsultasi/{konsultasi}/chat', [\App\Http\Controllers\ChatController::class, 'roomKonsultasi'])->name('konsultasi.chat');
    Route::post('/konsultasi/{konsultasi}/chat', [\App\Http\Controllers\ChatController::class, 'sendKonsultasiChat'])->name('konsultasi.chat.send');

    // Room chat transaksi (diakses petani & pedagang terkait, setelah ACC)
    Route::get('/pesanan/{pesanan}/chat', [\App\Http\Controllers\ChatController::class, 'roomTransaksi'])->name('transaksi.chat');
    Route::post('/pesanan/{pesanan}/chat', [\App\Http\Controllers\ChatController::class, 'sendTransaksiChat'])->name('transaksi.chat.send');

    // Petani
    Route::middleware('role:petani')->prefix('petani')->name('petani.')->group(function () {
        Route::get('/dashboard', [PetaniController::class, 'dashboard'])->name('dashboard');
        Route::get('/data', [PetaniController::class, 'dataPertanian'])->name('data');
        Route::post('/data', [PetaniController::class, 'storeTanaman'])->name('data.store');
        Route::delete('/data/{tanaman}', [PetaniController::class, 'destroyTanaman'])->name('data.destroy');
        Route::get('/data/{tanaman}/perawatan', [PetaniController::class, 'perawatan'])->name('perawatan');
        Route::post('/data/{tanaman}/perawatan', [PetaniController::class, 'storePerawatan'])->name('perawatan.store');
        Route::get('/katalog', [PetaniController::class, 'katalog'])->name('katalog');
        Route::post('/katalog', [PetaniController::class, 'storePanen'])->name('katalog.store');
        Route::put('/katalog/{panen}', [PetaniController::class, 'updatePanen'])->name('katalog.update');
        Route::delete('/katalog/{panen}', [PetaniController::class, 'destroyPanen'])->name('katalog.destroy');
        Route::get('/konsultasi', [PetaniController::class, 'konsultasi'])->name('konsultasi');
        Route::post('/konsultasi', [PetaniController::class, 'storeKonsultasi'])->name('konsultasi.store');
        Route::delete('/konsultasi/{konsultasi}', [PetaniController::class, 'destroyKonsultasi'])->name('konsultasi.destroy');
        Route::get('/laporan', [PetaniController::class, 'laporan'])->name('laporan');
        Route::post('/laporan', [PetaniController::class, 'storeLaporan'])->name('laporan.store');
        Route::get('/profile', [PetaniController::class, 'profile'])->name('profile');
        Route::put('/profile', [PetaniController::class, 'updateProfile'])->name('profile.update');
        Route::get('/pesanan', [PetaniController::class, 'pesananMasuk'])->name('pesanan');
        Route::put('/pesanan/{pesanan}/status', [PetaniController::class, 'konfirmasiPesanan'])->name('pesanan.status');
    });

    // Ahli
    Route::middleware('role:ahli')->prefix('ahli')->name('ahli.')->group(function () {
        Route::get('/dashboard', [AhliController::class, 'dashboard'])->name('dashboard');
        Route::get('/konsultasi', [AhliController::class, 'konsultasi'])->name('konsultasi');
        Route::post('/konsultasi/{konsultasi}/jawab', [AhliController::class, 'jawabKonsultasi'])->name('konsultasi.jawab');
        Route::get('/laporan', [AhliController::class, 'laporan'])->name('laporan');
        Route::post('/laporan', [AhliController::class, 'storeLaporan'])->name('laporan.store');
        Route::get('/profile', [AhliController::class, 'profile'])->name('profile');
        Route::put('/profile', [AhliController::class, 'updateProfile'])->name('profile.update');
    });

    // Pedagang
    Route::middleware('role:pedagang')->prefix('pedagang')->name('pedagang.')->group(function () {
        Route::get('/dashboard', [PedagangController::class, 'dashboard'])->name('dashboard');
        Route::get('/katalog', [PedagangController::class, 'katalog'])->name('katalog');
        Route::post('/katalog/{panen}/beli', [PedagangController::class, 'beli'])->name('katalog.beli');
        Route::get('/pesanan', [PedagangController::class, 'pesanan'])->name('pesanan');
        Route::get('/laporan', [PedagangController::class, 'laporan'])->name('laporan');
        Route::post('/laporan', [PedagangController::class, 'storeLaporan'])->name('laporan.store');
        Route::get('/profile', [PedagangController::class, 'profile'])->name('profile');
        Route::put('/profile', [PedagangController::class, 'updateProfile'])->name('profile.update');
    });

    // Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/pengguna', [AdminController::class, 'users'])->name('users');
        Route::post('/pengguna', [AdminController::class, 'storeUser'])->name('users.store');
        Route::put('/pengguna/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/pengguna/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::put('/pengguna/{user}/blokir', [AdminController::class, 'blokir'])->name('users.blokir');
        Route::put('/pengguna/{user}/unblokir', [AdminController::class, 'unblokir'])->name('users.unblokir');
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
        Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
        Route::put('/laporan/{laporan}/resolve', [AdminController::class, 'resolveLaporan'])->name('laporan.resolve');
        Route::put('/laporan/{laporan}/blokir', [AdminController::class, 'blokirFromLaporan'])->name('laporan.blokir');
        Route::get('/transaksi', [AdminController::class, 'transaksi'])->name('transaksi');
        Route::get('/konsultasi', [AdminController::class, 'konsultasi'])->name('konsultasi');
        Route::get('/konsultasi/{konsultasi}/chat', [AdminController::class, 'chatKonsultasi'])->name('konsultasi.chat');
    });
});
