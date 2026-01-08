<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'name',
        'tkt_jabatan',
        'unit_kerja',
        'no_hp_1',
        'no_hp_2',
        'no_hp_3',
        'foto_sekarang',
        'foto_lama',
    ];
}