<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return redirect('/admin/create-event'); // Redirect sementara untuk tes
});

// Route untuk Admin membuat acara (Sederhana untuk demo)
Route::get('/admin/create-event', function() {
    // Kode dummy untuk membuat event otomatis jika belum ada, agar bisa dites
    $event = \App\Models\Event::firstOrCreate([
        'title' => 'Rapat Koordinasi Tahunan',
        'date' => date('Y-m-d'),
        'time' => '09:00:00',
        'location' => 'Aula Utama'
    ]);
    return "Event Dibuat! Silahkan buka link absensi: <a href='/absensi/$event->id'>/absensi/$event->id</a>";
});

// Route Absensi (User)
Route::get('/absensi/{event}', [AttendanceController::class, 'showForm'])->name('attendance.form');
Route::post('/absensi/{event}', [AttendanceController::class, 'store'])->name('attendance.store');

// Route Download (Admin)
Route::get('/absensi/{event}/download-pdf', [AttendanceController::class, 'downloadPdf'])->name('attendance.pdf');