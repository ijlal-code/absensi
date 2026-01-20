<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'permissions', // Tambahkan ini
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
            'permissions' => 'array', // Pastikan dicast sebagai array
        ];
    }

    // Cek apakah user adalah admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Cek apakah user punya hak akses tertentu
    public function hasPermission($permission)
    {
        // Admin selalu boleh
        if ($this->isAdmin()) {
            return true;
        }

        // Jika permissions kosong, return false
        if (!$this->permissions) {
            return false;
        }

        // Cek apakah permission ada di dalam array
        return in_array($permission, $this->permissions);
    }
}