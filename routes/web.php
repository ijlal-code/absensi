<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Public Routes (Login & Absensi Tamu)
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman Absensi untuk Peserta (Tanpa Login)
Route::prefix('absensi')->group(function () {
    Route::get('/{event}', [AttendanceController::class, 'showForm'])->name('attendance.form');
    Route::post('/{event}', [AttendanceController::class, 'store'])->name('attendance.store');
});

/*
|--------------------------------------------------------------------------
| Dashboard Routes (Perlu Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Utama (Redirect otomatis di Controller berdasarkan Role)
    Route::get('/dashboard', [EventController::class, 'index'])->name('dashboard');

    // --- Menu Penyelenggara (Organizer) ---
    Route::get('/create-event', [EventController::class, 'create'])->name('event.create');
    Route::post('/store-event', [EventController::class, 'store'])->name('event.store');
    
    // Edit & Hapus Event (CRUD)
    Route::get('/event/{event}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/event/{event}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/event/{event}', [EventController::class, 'destroy'])->name('event.destroy');
    
    // QR Code Routes
    Route::get('/event/{event}/qrcode', [EventController::class, 'showQrCode'])->name('event.qrcode');
    Route::get('/event/{event}/qrcode/download', [EventController::class, 'downloadQrCode'])->name('event.qrcode.download');

    // Laporan & Monitoring
    Route::get('/reports', [EventController::class, 'reports'])->name('reports');
    Route::get('/event/{event}/monitor', [EventController::class, 'show'])->name('event.show');
    Route::get('/event/{event}/download-pdf', [AttendanceController::class, 'downloadPdf'])->name('attendance.pdf');

    // --- Menu Khusus Admin ---
    Route::middleware(['can:is_admin'])->prefix('admin')->name('admin.')->group(function() {
        Route::get('/dashboard', [EventController::class, 'adminDashboard'])->name('dashboard');
        Route::resource('users', UserController::class);
    });
});