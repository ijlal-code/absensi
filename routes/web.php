<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;

// Halaman Awal (Login)
Route::get('/', [AuthController::class, 'showLogin'])->name('login');

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Absensi (Publik / User Mengisi Absen)
Route::prefix('absensi')->group(function () {
    Route::get('/{event}', [AttendanceController::class, 'showForm'])->name('attendance.form');
    Route::post('/{event}', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/{event}/download-pdf', [AttendanceController::class, 'downloadPdf'])->name('attendance.pdf');
});

// Group Dashboard (Admin & Organizer)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Utama (Bisa diakses Admin & Organizer)
    Route::get('/dashboard', [EventController::class, 'index'])->name('dashboard');
    
    // Manajemen Acara (Create, Edit, Delete, Report)
    Route::get('/create-event', [EventController::class, 'create'])->name('event.create');
    Route::post('/store-event', [EventController::class, 'store'])->name('event.store');
    Route::get('/event/{event}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/event/{event}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/event/{event}', [EventController::class, 'destroy'])->name('event.destroy');
    Route::get('/event/{event}/monitor', [EventController::class, 'show'])->name('event.show');
    Route::get('/reports', [EventController::class, 'reports'])->name('reports');

    // Khusus Admin (Manajemen User)
    Route::middleware(['can:is_admin'])->group(function() {
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });
});