<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Informasi Utama Tambahan
            $table->string('band')->nullable()->after('tkt_jabatan');
            $table->string('email')->nullable()->after('unit_kerja');
            
            // Data Pribadi
            $table->date('tanggal_lahir')->nullable()->after('email');
            $table->string('kewarganegaraan')->default('Indonesia')->after('tanggal_lahir');
            $table->string('jenis_kelamin')->nullable()->after('kewarganegaraan'); // L/P
            $table->string('agama')->nullable()->after('jenis_kelamin');
            $table->string('status_perkawinan')->nullable()->after('agama');

            // Data Kepegawaian
            $table->date('tanggal_masuk')->nullable()->after('status_perkawinan');
            $table->date('tanggal_pensiun')->nullable()->after('tanggal_masuk');

            // Kontak & Lokasi Tambahan
            $table->text('alamat')->nullable()->after('tanggal_pensiun');
            $table->string('lokasi_kerja')->nullable()->after('alamat');
            $table->string('no_ext')->nullable()->after('lokasi_kerja');
        });
    }

    public function down(): void
    {
        // Drop kolom jika rollback (opsional)
    }
};