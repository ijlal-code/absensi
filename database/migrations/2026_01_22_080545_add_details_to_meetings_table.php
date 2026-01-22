<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->string('notulis')->nullable(); // Penulis Notulen
            $table->string('presenter')->nullable(); // Presenter di footer
            $table->string('meeting_duration')->nullable(); // Waktu di footer (misal: 90 Menit)
            $table->text('attendees_list')->nullable(); // Isi kolom List of Attendees
        });
    }

    public function down()
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn(['notulis', 'presenter', 'meeting_duration', 'attendees_list']);
        });
    }
};