<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee; // Pastikan pakai Model Employee
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Pakai Data Indonesia

        $jabatanList = ['Staff Administrasi', 'Supervisor', 'Kepala Seksi', 'Operator Lapangan', 'Teknisi Senior', 'General Manager'];
        $unitList    = ['Divisi SDM', 'Divisi Keuangan', 'Divisi Operasional', 'Divisi Pemasaran', 'Unit IT & Data', 'Logistik'];

        for ($i = 0; $i < 20; $i++) {
            Employee::create([
                'nik'           => $faker->unique()->numerify('EMP2024####'),
                'name'          => $faker->name(),
                'tkt_jabatan'   => $faker->randomElement($jabatanList),
                'unit_kerja'    => $faker->randomElement($unitList),
                'no_hp_1'       => $faker->phoneNumber(),
                'no_hp_2'       => $faker->optional()->phoneNumber(), // Optional
                'no_hp_3'       => null,
                'foto_sekarang' => null,
                'foto_lama'     => null,
            ]);
        }
    }
}