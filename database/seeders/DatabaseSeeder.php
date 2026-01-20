<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil Seeder Admin di sini
        $this->call([
            AdminUserSeeder::class,
            TonasaImportSeeder::class,
            // EmployeeSeeder::class, // (Jika ada seeder lain, urutkan disini)
        ]);
    }
}