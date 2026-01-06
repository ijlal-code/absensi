<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;

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

// Link Absensi untuk Peserta (Public)
Route::prefix('absensi')->group(function () {
    Route::get('/{event}', [AttendanceController::class, 'showForm'])->name('attendance.form');
    Route::post('/{event}', [AttendanceController::class, 'store'])->name('attendance.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Dashboard Area)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Utama (Akan diarahkan controller berdasarkan role)
    Route::get('/dashboard', [EventController::class, 'index'])->name('dashboard');

    // Manajemen Event (Bisa diakses Organizer & Admin)
    Route::get('/create-event', [EventController::class, 'create'])->name('event.create');
    Route::post('/store-event', [EventController::class, 'store'])->name('event.store');
    
    // Fitur CRUD Event yang sebelumnya hilang
    Route::get('/event/{event}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/event/{event}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/event/{event}', [EventController::class, 'destroy'])->name('event.destroy');
    
    // Monitoring & Laporan
    Route::get('/event/{event}/monitor', [EventController::class, 'show'])->name('event.show');
    Route::get('/reports', [EventController::class, 'reports'])->name('reports');
    Route::get('/event/{event}/download-pdf', [AttendanceController::class, 'downloadPdf'])->name('attendance.pdf');

    /*
    |--------------------------------------------------------------------------
    | Admin Only Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['can:is_admin'])->prefix('admin')->name('admin.')->group(function() {
        // Dashboard Khusus Admin
        Route::get('/dashboard', [EventController::class, 'adminDashboard'])->name('dashboard');
        
        // Kelola User
        Route::resource('users', UserController::class);
    });
});