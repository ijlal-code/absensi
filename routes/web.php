<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Group Route untuk User (Absensi)
Route::prefix('absensi')->group(function () {
    Route::get('/{event}', [AttendanceController::class, 'showForm'])->name('attendance.form');
    Route::post('/{event}', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/{event}/download-pdf', [AttendanceController::class, 'downloadPdf'])->name('attendance.pdf');
});

// Group Route untuk Admin
// Tambahkan ->middleware('auth') di sini jika sudah ada login sistem
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/create-event', [AdminController::class, 'create'])->name('create');
    Route::post('/store-event', [AdminController::class, 'store'])->name('store');
    Route::get('/show-event/{event}', [AdminController::class, 'show'])->name('show');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});