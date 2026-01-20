<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menggunakan updateOrCreate agar jika dijalankan 2x tidak error (duplicate)
        User::updateOrCreate(
            ['email' => 'admin@system.com'], // Cek berdasarkan email
            [
                'name' => 'Administrator Utama',
                'password' => Hash::make('password123'), // Ganti password sesuai keinginan
                'role' => 'admin',
                'permissions' => null, // Admin tidak butuh permission array karena isAdmin() = true
                
                // Isi dummy data kolom lain agar tidak error database
                'nik' => '999999',
                'tkt_jabatan' => 'Admin',
                'unit_kerja' => 'IT Dept',
                'no_hp_1' => '081234567890',
            ]
        );
    }
}