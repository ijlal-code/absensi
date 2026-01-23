<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeInfoController;
use App\Http\Controllers\EmployeeManagementController;
use App\Http\Controllers\MeetingController;

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
    
   // --- DASHBOARD USER ---
    Route::get('/dashboard', [EventController::class, 'index'])->name('dashboard');
    
    // Route Khusus List Agenda Admin
    Route::get('/agenda-resmi', [EventController::class, 'listAdminAgendas'])->name('events.admin_list');

    // --- FITUR BUAT AGENDA SENDIRI ---
    // Lindungi dengan permission 'create_events' agar user yang belum dicentang tidak bisa akses via URL
    Route::middleware(['permission:create_events'])->group(function () {
        Route::get('/manajemen-agenda', [EventController::class, 'agenda'])->name('event.agenda');
        Route::get('/create-event', [EventController::class, 'create'])->name('event.create');
        Route::post('/store-event', [EventController::class, 'store'])->name('event.store');
        Route::get('/event/{event}/edit', [EventController::class, 'edit'])->name('event.edit');
        Route::put('/event/{event}', [EventController::class, 'update'])->name('event.update');
        Route::delete('/event/{event}', [EventController::class, 'destroy'])->name('event.destroy');
    });

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
    // Rute Admin Users harus punya nama prefix 'admin.'
    Route::prefix('admin')->name('admin.')->middleware('permission:manage_users')->group(function() {
        Route::resource('users', UserController::class);
    });

    // === ROUTE AGENDA RAPAT (Terpisah) ===
    Route::prefix('rapat')->name('meetings.')->group(function () {
        Route::get('/', [MeetingController::class, 'index'])->name('index'); // Dashboard Rapat hari ini/list
        Route::get('/create', [MeetingController::class, 'create'])->name('create');
        Route::post('/', [MeetingController::class, 'store'])->name('store');
        Route::get('/{meeting}/edit', [MeetingController::class, 'edit'])->name('edit');
        Route::put('/{meeting}', [MeetingController::class, 'update'])->name('update');
        Route::delete('/{meeting}', [MeetingController::class, 'destroy'])->name('destroy');
        
        // Halaman Laporan & PDF
        Route::get('/reports', [MeetingController::class, 'reports'])->name('reports');
        Route::get('/{meeting}/pdf', [MeetingController::class, 'downloadPdf'])->name('pdf');

        // API untuk mencari karyawan berdasarkan SAP/Nama
    Route::get('/api/search-employee', [MeetingController::class, 'searchEmployee'])->name('search.employee');

    // --- MANAJEMEN LOKASI (Route Baru) ---
    Route::get('/manajemen-lokasi', [MeetingController::class, 'manageLocations'])->name('locations.index'); // Halaman List
    Route::post('/location', [MeetingController::class, 'storeLocation'])->name('location.store');
    Route::delete('/location/{id}', [MeetingController::class, 'destroyLocation'])->name('location.destroy');
    });
});