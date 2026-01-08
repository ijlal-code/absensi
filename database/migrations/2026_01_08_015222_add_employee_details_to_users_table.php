<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik')->nullable()->after('email');
            $table->string('tkt_jabatan')->nullable()->after('nik');
            $table->string('unit_kerja')->nullable()->after('tkt_jabatan');
            $table->string('no_hp_1')->nullable()->after('unit_kerja');
            $table->string('no_hp_2')->nullable()->after('no_hp_1');
            $table->string('no_hp_3')->nullable()->after('no_hp_2');
            $table->string('foto_sekarang')->nullable()->after('no_hp_3'); // Path foto
            $table->string('foto_lama')->nullable()->after('foto_sekarang'); // Path foto
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nik', 
                'tkt_jabatan', 
                'unit_kerja', 
                'no_hp_1', 
                'no_hp_2', 
                'no_hp_3',
                'foto_sekarang',
                'foto_lama'
            ]);
        });
    }
};