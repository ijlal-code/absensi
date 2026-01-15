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
            
            // --- KOLOM DATA CSV ---
            $table->string('sap_id')->nullable();
            $table->string('nik')->nullable()->index();
            $table->string('position_code')->nullable();
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->string('subgroup')->nullable();
            $table->string('cost_ctr')->nullable();
            $table->string('direktorat')->nullable();
            $table->string('departemen')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->string('seksi')->nullable();
            
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin')->nullable();
            
            $table->string('personnel_area')->nullable();
            $table->string('abrev_position')->nullable();
            $table->string('abrev_organization')->nullable();
            $table->string('obj_dept')->nullable();
            $table->string('obj_biro')->nullable();
            $table->string('obj_sect')->nullable();
            $table->string('obj_grp')->nullable();
            $table->string('organizational_unit')->nullable();
            $table->string('cost_center_text')->nullable();
            
            // PERUBAHAN DI SINI: ganti tanggal_pensiun jadi date_terminasi
            $table->date('date_terminasi')->nullable(); // Col 22
            
            $table->string('email')->nullable();
            $table->string('agama')->nullable();
            
            $table->string('umur')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('pendidikan')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->date('s_d')->nullable();
            $table->string('masa_kerja')->nullable();
            $table->text('alamat')->nullable();
            $table->string('band')->nullable();

            $table->string('no_hp_1')->nullable();
            $table->string('no_hp_2')->nullable();
            $table->string('no_hp_3')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tonasa_employees');
    }
};