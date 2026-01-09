<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TonasaEmployee extends Model
{
    use HasFactory;

    protected $table = 'tonasa_employees';

    protected $fillable = [
        'nik', 'sap_id', 'nama', 'jabatan', 'unit_kerja',
        'tanggal_lahir', 'jenis_kelamin', 'agama', 
        'alamat', 'email', 'tempat_lahir', 'pendidikan'
    ];

    // Casting agar tanggal_lahir otomatis jadi object Carbon (bukan string)
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
}