<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeInfoController;
use App\Http\Controllers\EmployeeManagementController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('absensi')->group(function () {
    Route::get('/{event}', [AttendanceController::class, 'showForm'])->name('attendance.form');
    Route::post('/{event}', [AttendanceController::class, 'store'])->name('attendance.store');
});

/*
|--------------------------------------------------------------------------
| Dashboard Routes (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // 1. Route Informasi Karyawan (View Only / Publik)
    Route::get('/informasi-karyawan', [EmployeeInfoController::class, 'index'])->name('employees.index');

    // --- PERUBAHAN DI SINI ---
    // Route Statistik Karyawan (Menu Terpisah)
    Route::get('/statistik-karyawan', [EmployeeManagementController::class, 'stats'])->name('employee.stats');

    // 2. Route Kelola Karyawan (CRUD Lengkap)
    // Note: Route 'stats' yang lama dihapus dari sini agar tidak konflik
    Route::resource('employee-management', EmployeeManagementController::class);
    // -------------------------

    // Dashboard Utama
    Route::get('/dashboard', [EventController::class, 'index'])->name('dashboard');

    // Menu Penyelenggara
    Route::get('/create-event', [EventController::class, 'create'])->name('event.create');
    Route::post('/store-event', [EventController::class, 'store'])->name('event.store');
    Route::get('/event/{event}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/event/{event}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/event/{event}', [EventController::class, 'destroy'])->name('event.destroy');
    
    Route::get('/event/{event}/qrcode', [EventController::class, 'showQrCode'])->name('event.qrcode');
    Route::get('/event/{event}/qrcode/download', [EventController::class, 'downloadQrCode'])->name('event.qrcode.download');
    
    Route::get('/reports', [EventController::class, 'reports'])->name('reports');
    Route::get('/event/{event}/monitor', [EventController::class, 'show'])->name('event.show');
    Route::get('/event/{event}/download-pdf', [AttendanceController::class, 'downloadPdf'])->name('attendance.pdf');

    // Menu Admin (User Management)
    Route::middleware(['can:is_admin'])->prefix('admin')->name('admin.')->group(function() {
        Route::get('/dashboard', [EventController::class, 'adminDashboard'])->name('dashboard');
        Route::resource('users', UserController::class);
    });
});