<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TonasaEmployee extends Model
{
    use HasFactory;

    protected $table = 'tonasa_employees';

    protected $fillable = [
        'nik', 'sap_id', 'nama', 'jabatan', 'unit_kerja', 'departemen',
        'tanggal_lahir', 'jenis_kelamin', 'agama', 'alamat', 'email',
        'tempat_lahir', 'pendidikan', 'tanggal_masuk', 'tanggal_pensiun', 'band'
    ];

    // Casting tanggal agar fitur ulang tahun dan format tanggal berfungsi
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'tanggal_pensiun' => 'date',
    ];
}