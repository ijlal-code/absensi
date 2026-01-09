<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('tonasa_employees');

        Schema::create('tonasa_employees', function (Blueprint $table) {
            $table->id();
            
            // --- DATA UMUM (Tampil di Tabel Informasi Karyawan) ---
            $table->string('nik')->nullable()->index();  // Kolom 1
            $table->string('sap_id')->nullable();        // Kolom 0
            $table->string('nama');                      // Kolom 3
            $table->string('jabatan')->nullable();       // Kolom 4 (Position Teks)
            $table->string('unit_kerja')->nullable();    // Kolom 9 (TXT_BIRO)
            $table->string('departemen')->nullable();    // Kolom 8 (TXT_DEPT)
            
            // --- DATA DETAIL / SENSITIF (Hanya di Popup Detail) ---
            $table->date('tanggal_lahir')->nullable();   // Kolom 11
            $table->string('jenis_kelamin')->nullable(); // Kolom 12
            $table->string('agama')->nullable();         // Kolom 24
            $table->text('alamat')->nullable();          // Kolom 32
            $table->string('email')->nullable();         // Kolom 23
            $table->string('tempat_lahir')->nullable();  // Kolom 27
            $table->string('pendidikan')->nullable();    // Kolom 28
            $table->date('tanggal_masuk')->nullable();   // Kolom 29 (Organilk)
            $table->date('tanggal_pensiun')->nullable(); // Kolom 22 (Date Terminasi)
            $table->string('band')->nullable();          // Kolom 33
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tonasa_employees');
    }
};