<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TonasaEmployee extends Model
{
    use HasFactory;
    protected $table = 'tonasa_employees';
    protected $guarded = ['id']; // Allow mass assignment

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'tanggal_pensiun' => 'date',
    ];
}