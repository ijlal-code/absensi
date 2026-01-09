<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $jabatan = ['Staff', 'Supervisor', 'Manager', 'General Manager'];
        $unit    = ['SDM', 'Keuangan', 'Operasional', 'IT', 'Marketing'];
        $agama   = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha'];
        $status  = ['Menikah', 'Belum Menikah', 'Cerai'];
        $band    = ['I', 'II', 'III', 'IV', 'V'];

        for ($i = 0; $i < 20; $i++) {
            Employee::create([
                'nik' => $faker->unique()->numerify('########'),
                'name' => $faker->name(),
                'tkt_jabatan' => $faker->randomElement($jabatan),
                'band' => $faker->randomElement($band),
                'unit_kerja' => $faker->randomElement($unit),
                'email' => $faker->email(),
                
                'tanggal_lahir' => $faker->date('Y-m-d', '-25 years'),
                'kewarganegaraan' => 'Indonesia',
                'jenis_kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'agama' => $faker->randomElement($agama),
                'status_perkawinan' => $faker->randomElement($status),
                
                'tanggal_masuk' => $faker->date('Y-m-d', '-5 years'),
                'tanggal_pensiun' => $faker->date('Y-m-d', '+10 years'),
                
                'alamat' => $faker->address(),
                'lokasi_kerja' => 'Kantor Pusat Tonasa',
                'no_ext' => $faker->numerify('####'),
                
                // PERBAIKAN DI SINI: Mengisi no_hp 1, 2, dan 3
                'no_hp_1' => $faker->phoneNumber(),
                'no_hp_2' => $faker->phoneNumber(), 
                'no_hp_3' => $faker->phoneNumber(),
                
                'foto_sekarang' => null, 
                'foto_lama' => null,
            ]);
        }
    }
}