<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LayananPenyewaController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PenyewaController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\PeraturanController;

// Public
Route::get('/', [DashboardController::class, 'index']);
Route::get('/peraturan', [PeraturanController::class, 'publik']);
//Route::get('/kamar/{id}', [DashboardController::class, 'detailKamar']);
Route::get('/kamar', [DashboardController::class, 'kamarFasilitas']);
Route::get('/kamar/{tipe}', [DashboardController::class, 'detailKamar']);

//Auth
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'registerForm']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Penyewa nonaktif
Route::middleware('auth:penyewa')->group(function () {
    Route::get('/reservasi', [ReservasiController::class, 'index']);
    Route::post('/reservasi', [ReservasiController::class, 'store']);
    Route::get('/pengaduan/create', [LayananPenyewaController::class, 'createPengaduan']);
    Route::post('/pengaduan', [LayananPenyewaController::class, 'store']);
    Route::get('/cek-status', [ReservasiController::class, 'cekStatus']);
});

//Layanan Penyewa
Route::middleware(['auth:penyewa', 'penyewa.aktif'])->group(function () {
    Route::get('/perpanjangan', [LayananPenyewaController::class, 'perpanjangan']);
    Route::post('/perpanjangan', [LayananPenyewaController::class, 'storePerpanjangan']);
    Route::get('/pengaduan', [LayananPenyewaController::class, 'pengaduan']);
    Route::post('/pengaduan', [LayananPenyewaController::class, 'storePengaduan']);
});

//Admin
Route::middleware('admin')->prefix('admin')->group(function () {
    
    // Keuangan
    Route::get('/keuangan', [KeuanganController::class, 'index']);
    Route::get('/pengeluaran/create', [PengeluaranController::class, 'create']);
    Route::post('/pengeluaran', [PengeluaranController::class, 'store']);
    Route::get('/pengeluaran/{id}/edit', [PengeluaranController::class, 'edit']);
    Route::put('/pengeluaran/{id}', [PengeluaranController::class, 'update']);
    Route::delete('/pengeluaran/{id}', [PengeluaranController::class, 'destroy']);

    // Pengaduan
    Route::get('/pengaduan', [PengaduanController::class, 'index']);
    Route::get('/pengaduan/{id}/edit', [PengaduanController::class, 'edit']);
    Route::put('/pengaduan/{id}', [PengaduanController::class, 'update']);

    // Verifikasi Pembayaran
    Route::get('/verifikasi', [PembayaranController::class, 'index']);
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show']);
    Route::put('/pembayaran/{id}', [PembayaranController::class, 'update']);

    // Data Penyewa 
    Route::get('/penyewa', [PenyewaController::class, 'index']);
    Route::get('/penyewa/{id}/edit', [PenyewaController::class, 'edit']);
    Route::put('/penyewa/{id}', [PenyewaController::class, 'update']);

    // Data Kos
    Route::get('/data', [DataController::class, 'index']);

    // Kamar
    Route::get('/kamar/create', [KamarController::class, 'create']);
    Route::post('/kamar', [KamarController::class, 'store']);
    Route::get('/kamar/{id}/edit', [KamarController::class, 'edit']);
    Route::put('/kamar/{id}', [KamarController::class, 'update']);
    Route::delete('/kamar/{id}', [KamarController::class, 'destroy']);

    // Fasilitas
    Route::get('/fasilitas/create', [FasilitasController::class, 'create']);
    Route::post('/fasilitas', [FasilitasController::class, 'store']);
    Route::get('/fasilitas/{id}/edit', [FasilitasController::class, 'edit']);
    Route::put('/fasilitas/{id}', [FasilitasController::class, 'update']);
    Route::delete('/fasilitas/{id}', [FasilitasController::class, 'destroy']);

    // Bank
    Route::get('/bank/create', [BankController::class, 'create']);
    Route::post('/bank', [BankController::class, 'store']);
    Route::get('/bank/{id}/edit', [BankController::class, 'edit']);
    Route::put('/bank/{id}', [BankController::class, 'update']);
    Route::delete('/bank/{id}', [BankController::class, 'destroy']);

    // Peraturan
    Route::get('/peraturan/create', [PeraturanController::class, 'create']);
    Route::post('/peraturan', [PeraturanController::class, 'store']);
    Route::get('/peraturan/{id}/edit', [PeraturanController::class, 'edit']);
    Route::put('/peraturan/{id}', [PeraturanController::class, 'update']);
    Route::delete('/peraturan/{id}', [PeraturanController::class, 'destroy']);
});

