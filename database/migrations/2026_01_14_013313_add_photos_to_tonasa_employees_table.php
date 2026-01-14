<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('tonasa_employees', function (Blueprint $table) {
        $table->string('foto_terbaru')->nullable()->after('email');
        $table->string('foto_lama')->nullable()->after('foto_terbaru');
    });
}

public function down()
{
    Schema::table('tonasa_employees', function (Blueprint $table) {
        $table->dropColumn(['foto_terbaru', 'foto_lama']);
    });
}
};
