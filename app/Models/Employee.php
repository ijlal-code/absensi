<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

   protected $fillable = [
    'nik', 'name', 'tkt_jabatan', 'band', 'unit_kerja', 'email',
    'tanggal_lahir', 'kewarganegaraan', 'jenis_kelamin', 'agama', 'status_perkawinan',
    'tanggal_masuk', 'tanggal_pensiun',
    'alamat', 'lokasi_kerja', 'no_ext', 'no_hp_1', 'no_hp_2', 'no_hp_3',
    'foto_sekarang', 'foto_lama',
];
}