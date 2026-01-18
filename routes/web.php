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

// Route Absensi Publik (Dapat diakses peserta tanpa login admin)
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
    // Halaman list agenda & tombol buat agenda
    Route::get('/manajemen-agenda', [EventController::class, 'agenda'])->name('event.agenda');

    // --- 3. Manajemen Karyawan & Statistik ---
    // View Informasi Publik Karyawan (Read Only / Tampilan Card)
    Route::get('/informasi-karyawan', [EmployeeInfoController::class, 'index'])->name('employees.index');
    
    // Halaman Statistik Visual
    Route::get('/statistik-karyawan', [EmployeeManagementController::class, 'stats'])->name('employee-management.stats');

    // CRUD Data Karyawan (Full Akses: Create, Read, Update, Delete)
    // Route resource ini otomatis membuat route untuk index, create, store, show, edit, update, destroy
    Route::resource('employee-management', EmployeeManagementController::class);

    // Menu Penyelenggara
    Route::get('/create-event', [EventController::class, 'create'])->name('event.create');
    Route::post('/store-event', [EventController::class, 'store'])->name('event.store');
    Route::get('/event/{event}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/event/{event}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/event/{event}', [EventController::class, 'destroy'])->name('event.destroy');
    
    Route::get('/event/{event}/qrcode', [EventController::class, 'showQrCode'])->name('event.qrcode');
    Route::get('/event/{event}/qrcode/download', [EventController::class, 'downloadQrCode'])->name('event.qrcode.download');
    
    Route::get('/reports', [EventController::class, 'reports'])->name('reports');

// GANTI menjadi seperti ini:
Route::get('/event/{event}/show', [EventController::class, 'show'])->name('event.show'); // Untuk detail/modal
Route::get('/event/{event}/monitor', [EventController::class, 'monitor'])->name('event.monitor'); // Khusus Monitoring
    Route::get('/event/{event}/download-pdf', [AttendanceController::class, 'downloadPdf'])->name('attendance.pdf');

    // --- 5. Admin Management (User System) ---
    Route::middleware(['can:is_admin'])->prefix('admin')->name('admin.')->group(function() {
        Route::resource('users', UserController::class);
    });
});