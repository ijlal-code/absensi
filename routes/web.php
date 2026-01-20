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
| Public Routes (Login, Register, Form Absensi)
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Absensi Publik (Dapat diakses peserta tanpa login)
Route::prefix('absensi')->group(function () {
    Route::get('/{event}', [AttendanceController::class, 'showForm'])->name('attendance.form');
    Route::post('/{event}', [AttendanceController::class, 'store'])->name('attendance.store');
});

/*
|--------------------------------------------------------------------------
| Dashboard Routes (Authentication Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // --- 1. Dashboard Utama (Menu Navigasi) ---
    Route::get('/dashboard', [EventController::class, 'index'])->name('dashboard');

    // --- 2. Manajemen Agenda (Halaman Operasional) ---
    Route::get('/manajemen-agenda', [EventController::class, 'agenda'])->name('event.agenda');
    Route::get('/create-event', [EventController::class, 'create'])->name('event.create');
    Route::post('/store-event', [EventController::class, 'store'])->name('event.store');
    Route::get('/event/{event}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/event/{event}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/event/{event}', [EventController::class, 'destroy'])->name('event.destroy');

    // Fitur Event lainnya
    Route::get('/event/{event}/qrcode', [EventController::class, 'showQrCode'])->name('event.qrcode');
    Route::get('/event/{event}/qrcode/download', [EventController::class, 'downloadQrCode'])->name('event.qrcode.download');
    Route::get('/event/{event}/show', [EventController::class, 'show'])->name('event.show'); 
    Route::get('/event/{event}/monitor', [EventController::class, 'monitor'])->name('event.monitor');
    Route::get('/event/{event}/download-pdf', [AttendanceController::class, 'downloadPdf'])->name('attendance.pdf');


    // --- 3. FITUR DENGAN PEMBATASAN AKSES (MIDDLEWARE PERMISSION) ---

    // A. Informasi Karyawan
    Route::get('/informasi-karyawan', [EmployeeInfoController::class, 'index'])
        ->middleware('permission:view_employees')
        ->name('employees.index');
    
    // B. Statistik Karyawan
    Route::get('/statistik-karyawan', [EmployeeManagementController::class, 'stats'])
        ->middleware('permission:view_statistics')
        ->name('employee-management.stats');

    // C. Kelola Karyawan CRUD
    Route::resource('employee-management', EmployeeManagementController::class)
        ->middleware('permission:manage_employees');

    // D. Laporan
    Route::get('/reports', [EventController::class, 'reports'])
        ->middleware('permission:view_reports')
        ->name('reports');

    // --- 4. Admin Management (User System) ---
    // HANYA BISA DIAKSES ADMIN
    // Kita gunakan 'permission:manage_users'. 
    // Admin lolos karena bypass. User biasa ditolak karena tidak punya hak ini.
    Route::prefix('admin')->name('admin.')->middleware('permission:manage_users')->group(function() {
        Route::resource('users', UserController::class);
    });
});