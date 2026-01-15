<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Agenda
       Schema::create('events', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // <--- TAMBAHKAN INI
    $table->string('title');
    $table->date('date');
    $table->time('start_time');
    $table->time('end_time');
    $table->string('location');
    $table->timestamps();
});

        // Tabel Absensi
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nama
            $table->string('work_unit'); // Unit Kerja
            $table->string('signature_path'); // Path file tanda tangan (baik gambar/upload)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('events');
    }
};