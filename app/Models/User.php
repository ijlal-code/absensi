<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // app/Models/User.php

protected $fillable = [
    'name',
    'email',
    'password',
    'role', // pastikan ini ada jika Anda pakai role
    'nik',
    'tkt_jabatan',
    'unit_kerja',
    'no_hp_1',
    'no_hp_2',
    'no_hp_3',
    'foto_sekarang',
    'foto_lama',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Helper untuk cek apakah user adalah admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}