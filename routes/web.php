<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaAuthController;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Controllers\SenseiAuthController;
use App\Http\Controllers\SenseiDashboardController;

/*
|--------------------------------------------------------------------------
| Siswa Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest:siswa')->group(function () {
    Route::get('/siswa/login', [SiswaAuthController::class, 'showLoginForm'])->name('siswa.login');
    Route::post('/siswa/login', [SiswaAuthController::class, 'login']);
    Route::get('/siswa/register', [SiswaAuthController::class, 'showRegisterForm'])->name('siswa.register');
    Route::post('/siswa/register', [SiswaAuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Siswa Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:siswa')->group(function () {
    Route::post('/siswa/logout', [SiswaAuthController::class, 'logout'])->name('siswa.logout');

    Route::get('/', [SiswaDashboardController::class, 'index'])->name('siswa.beranda');
    Route::get('/pembelajaran', [SiswaDashboardController::class, 'pembelajaran'])->name('siswa.pembelajaran');
    Route::get('/nilai', [SiswaDashboardController::class, 'nilai'])->name('siswa.nilai');
    Route::get('/evaluasi', [SiswaDashboardController::class, 'evaluasi'])->name('siswa.evaluasi');
    Route::get('/pembayaran', [SiswaDashboardController::class, 'pembayaran'])->name('siswa.pembayaran');
    Route::get('/berkas', [SiswaDashboardController::class, 'berkas'])->name('siswa.berkas');
    Route::get('/informasi', [SiswaDashboardController::class, 'informasi'])->name('siswa.informasi');
    Route::get('/profil', [SiswaDashboardController::class, 'profil'])->name('siswa.profil');
});

/*
|--------------------------------------------------------------------------
| Sensei Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest:sensei')->group(function () {
    Route::get('/sensei/login', [SenseiAuthController::class, 'showLoginForm'])->name('sensei.login');
    Route::post('/sensei/login', [SenseiAuthController::class, 'login']);
});

Route::middleware('auth:sensei')->prefix('sensei')->name('sensei.')->group(function () {
    Route::post('/logout', [SenseiAuthController::class, 'logout'])->name('logout');

    Route::get('/', [SenseiDashboardController::class, 'index'])->name('beranda');
    Route::get('/kelas', [SenseiDashboardController::class, 'kelas'])->name('kelas');
    Route::get('/pembelajaran', [SenseiDashboardController::class, 'pembelajaran'])->name('pembelajaran');
    Route::get('/penilaian', [SenseiDashboardController::class, 'penilaian'])->name('penilaian');
    Route::get('/evaluasi', [SenseiDashboardController::class, 'evaluasi'])->name('evaluasi');
    Route::get('/profil', [SenseiDashboardController::class, 'profil'])->name('profil');
});
