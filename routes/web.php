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
    Route::post('/absensi/hadir', [SiswaDashboardController::class, 'submitHadir'])->name('siswa.absensi.hadir');
    Route::post('/absensi/izin', [SiswaDashboardController::class, 'submitIzin'])->name('siswa.absensi.izin');
    Route::get('/kalender', [SiswaDashboardController::class, 'kalender'])->name('siswa.kalender');
    Route::post('/pembelajaran/tugas', [SiswaDashboardController::class, 'submitTugas'])->name('siswa.pembelajaran.tugas');
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

    Route::post('/pembelajaran/absensi', [SenseiDashboardController::class, 'generateAbsensi'])->name('pembelajaran.absensi.generate');
    Route::get('/pembelajaran/absensi/{session_id}', [SenseiDashboardController::class, 'showAbsensi'])->name('pembelajaran.absensi.show');
    Route::get('/pembelajaran/absensi/{session_id}/live', [SenseiDashboardController::class, 'liveAbsensi'])->name('pembelajaran.absensi.live');

    Route::post('/pembelajaran/materi', [SenseiDashboardController::class, 'storeMateri'])->name('pembelajaran.materi.store');
    Route::delete('/pembelajaran/materi/{id}', [SenseiDashboardController::class, 'destroyMateri'])->name('pembelajaran.materi.destroy');

    Route::post('/pembelajaran/jadwal', [SenseiDashboardController::class, 'storeJadwal'])->name('pembelajaran.jadwal.store');
    Route::delete('/pembelajaran/jadwal/{id}', [SenseiDashboardController::class, 'destroyJadwal'])->name('pembelajaran.jadwal.destroy');
    
    Route::post('/evaluasi/store', [SenseiDashboardController::class, 'storeEvaluasiOld'])->name('evaluasi.store');
    Route::post('/evaluasi/score', [SenseiDashboardController::class, 'storeEvaluasiScore'])->name('evaluasi.score.store');

    // New per-subject evaluasi system
    Route::post('/penilaian/evaluasi/store', [SenseiDashboardController::class, 'storeEvaluasi'])->name('penilaian.evaluasi.store');
    Route::put('/penilaian/evaluasi/{type}/{id}', [SenseiDashboardController::class, 'updateEvaluasi'])->name('penilaian.evaluasi.update');
    Route::delete('/penilaian/evaluasi/{type}/{id}', [SenseiDashboardController::class, 'destroyEvaluasi'])->name('penilaian.evaluasi.destroy');
    Route::post('/penilaian/nilai/store', [SenseiDashboardController::class, 'storeNilai'])->name('penilaian.nilai.store');
});
