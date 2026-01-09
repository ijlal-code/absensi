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
            
            // --- KOLOM DATA CSV (LENGKAP) ---
            $table->string('sap_id')->nullable();           // Col 0
            $table->string('nik')->nullable()->index();     // Col 1
            $table->string('position_code')->nullable();    // Col 2 (Position Angka)
            $table->string('nama');                         // Col 3
            $table->string('jabatan')->nullable();          // Col 4 (Position Teks)
            $table->string('subgroup')->nullable();         // Col 5
            $table->string('cost_ctr')->nullable();         // Col 6
            $table->string('direktorat')->nullable();       // Col 7 (TXT_DIR)
            $table->string('departemen')->nullable();       // Col 8 (TXT_DEPT)
            $table->string('unit_kerja')->nullable();       // Col 9 (TXT_BIRO)
            $table->string('seksi')->nullable();            // Col 10 (TXT_SECT)
            
            // --- DATA MERAH (PRIBADI) ---
            $table->date('tanggal_lahir')->nullable();      // Col 11
            $table->string('jenis_kelamin')->nullable();    // Col 12
            
            // --- DATA TEKNIS ---
            $table->string('personnel_area')->nullable();       // Col 13
            $table->string('abrev_position')->nullable();       // Col 14
            $table->string('abrev_organization')->nullable();   // Col 15
            $table->string('obj_dept')->nullable();             // Col 16
            $table->string('obj_biro')->nullable();             // Col 17
            $table->string('obj_sect')->nullable();             // Col 18
            $table->string('obj_grp')->nullable();              // Col 19
            $table->string('organizational_unit')->nullable();  // Col 20
            $table->string('cost_center_text')->nullable();     // Col 21
            
            $table->date('tanggal_pensiun')->nullable();        // Col 22
            $table->string('email')->nullable();                // Col 23 (Merah)
            $table->string('agama')->nullable();                // Col 24 (Merah)
            
            $table->string('umur')->nullable();                 // Col 26
            $table->string('tempat_lahir')->nullable();         // Col 27 (Merah)
            $table->string('pendidikan')->nullable();           // Col 28 (Merah)
            $table->date('tanggal_masuk')->nullable();          // Col 29 (Organilk)
            $table->string('masa_kerja')->nullable();           // Col 31
            $table->text('alamat')->nullable();                 // Col 32 (Merah)
            $table->string('band')->nullable();                 // Col 33

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tonasa_employees');
    }
};