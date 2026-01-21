<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel Header Rapat
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pembuat
            $table->string('type_of_meeting'); // Contoh: Rapat Internal Dept IA
            $table->string('facilitator'); // M. Amiruddin H.A
            $table->date('date'); // Tanggal Rapat
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('location');
            $table->timestamps();
        });

        // Tabel Action Items (Tindak Lanjut)
        Schema::create('action_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained()->onDelete('cascade');
            $table->text('action_item'); // Isi kegiatan/tindak lanjut
            $table->string('pic')->nullable(); // Penanggung Jawab
            $table->date('deadline')->nullable(); // Deadline
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('action_items');
        Schema::dropIfExists('meetings');
    }
};