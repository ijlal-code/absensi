<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;

// --- Public Routes ---
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Absensi (User Umum mengisi absen) ---
Route::prefix('absensi')->group(function () {
    Route::get('/{event}', [AttendanceController::class, 'showForm'])->name('attendance.form');
    Route::post('/{event}', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/{event}/download-pdf', [AttendanceController::class, 'downloadPdf'])->name('attendance.pdf');
});

// --- Area Dashboard (Perlu Login) ---
Route::middleware(['auth'])->group(function () {
    
    // Dashboard User (Penyelenggara)
    Route::get('/dashboard', [EventController::class, 'index'])->name('dashboard');

    // Manajemen Event (Bisa diakses User & Admin)
    Route::get('/create-event', [EventController::class, 'create'])->name('event.create');
    Route::post('/store-event', [EventController::class, 'store'])->name('event.store');
    
    // Fitur Edit, Hapus, Monitor
    Route::get('/event/{event}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/event/{event}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/event/{event}', [EventController::class, 'destroy'])->name('event.destroy');
    Route::get('/event/{event}/monitor', [EventController::class, 'show'])->name('event.show');
    
    // Laporan
    Route::get('/reports', [EventController::class, 'reports'])->name('reports');

    // --- Khusus Admin ---
    Route::middleware(['can:is_admin'])->prefix('admin')->name('admin.')->group(function() {
        // Admin Dashboard (Route khusus admin melihat semua)
        Route::get('/dashboard', [EventController::class, 'adminDashboard'])->name('dashboard');
        
        // Kelola User
        Route::resource('users', UserController::class)->except(['show', 'edit', 'update']);
    });
});