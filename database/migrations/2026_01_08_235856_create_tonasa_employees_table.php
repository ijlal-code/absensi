<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tonasa_employees', function (Blueprint $table) {
            $table->id();
            
            // --- DATA UTAMA (Tampil di Tabel) ---
            $table->string('nik')->unique();          // Kolom: NIK
            $table->string('sap_id')->nullable();     // Kolom: SAP
            $table->string('nama');                   // Kolom: NAMA KARYAWAN
            $table->string('jabatan')->nullable();    // Kolom: Position
            $table->string('unit_kerja')->nullable(); // Kolom: TXT_BIRO / TXT_DEPT
            
            // --- DATA DETAIL (Background Merah / Private) ---
            $table->date('tanggal_lahir')->nullable();  // Kolom: Birth date
            $table->string('jenis_kelamin')->nullable();// Kolom: Gender Key
            $table->string('agama')->nullable();        // Kolom: Religious
            $table->text('alamat')->nullable();         // Kolom: Alamat
            $table->string('email')->nullable();        // Kolom: E-mail
            $table->string('tempat_lahir')->nullable(); // Kolom: Tempat Lahir
            $table->string('pendidikan')->nullable();   // Kolom: Pendidikan
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tonasa_employees');
    }
};