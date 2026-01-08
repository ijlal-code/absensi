<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        
        // UBAH BARIS INI:
        // Menggunakan string dengan panjang 8 dan unique
        $table->char('nik', 8)->unique(); 
        
        $table->string('name');
        $table->string('tkt_jabatan');
        $table->string('unit_kerja');
        $table->string('no_hp_1');
        $table->string('no_hp_2')->nullable();
        $table->string('no_hp_3')->nullable();
        $table->string('foto_sekarang')->nullable();
        $table->string('foto_lama')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};