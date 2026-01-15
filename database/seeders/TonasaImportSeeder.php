<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TonasaEmployee;
use Carbon\Carbon;

class TonasaImportSeeder extends Seeder
{
    public function run()
    {
        $csvPath = base_path('DATABASE KARYAWAN SEMEN TONASA 2026.xlsx - Sheet1.csv'); 

        TonasaEmployee::truncate();

        if (file_exists($csvPath)) {
            $file = fopen($csvPath, 'r');
            $header = fgetcsv($file); 

            $this->command->info('Importing data from CSV...');

            while (($row = fgetcsv($file)) !== false) {
                if (empty($row[3])) continue;

                $parseDate = function($val) {
                    if (empty($val)) return null;
                    try { return Carbon::createFromFormat('m/d/Y', $val)->format('Y-m-d'); } 
                    catch (\Exception $e) { 
                        try { return Carbon::parse($val)->format('Y-m-d'); } catch (\Exception $ex) { return null; }
                    }
                };

                TonasaEmployee::create([
                    'sap_id'            => $row[0] ?? null,
                    'nik'               => $row[1] ?? null,
                    'position_code'     => $row[2] ?? null,
                    'nama'              => $row[3], 
                    'jabatan'           => $row[4] ?? null,
                    'subgroup'          => $row[5] ?? null,
                    'cost_ctr'          => $row[6] ?? null,
                    'direktorat'        => $row[7] ?? null,
                    'departemen'        => $row[8] ?? null,
                    'unit_kerja'        => $row[9] ?? null,
                    'seksi'             => $row[10] ?? null,
                    'tanggal_lahir'     => $parseDate($row[11]),
                    'jenis_kelamin'     => $row[12] ?? null,
                    'personnel_area'    => $row[13] ?? null,
                    'abrev_position'    => $row[14] ?? null,
                    'abrev_organization'=> $row[15] ?? null,
                    'obj_dept'          => $row[16] ?? null,
                    'obj_biro'          => $row[17] ?? null,
                    'obj_sect'          => $row[18] ?? null,
                    'obj_grp'           => $row[19] ?? null,
                    'organizational_unit'=> $row[20] ?? null,
                    'cost_center_text'  => $row[21] ?? null,
                    
                    // PERUBAHAN DI SINI
                    'date_terminasi'    => $parseDate($row[22]), // Col 22
                    
                    'email'             => $row[23] ?? null,
                    'agama'             => $row[24] ?? null,
                    'umur'              => $row[26] ?? null,
                    'tempat_lahir'      => $row[27] ?? null,
                    'pendidikan'        => $row[28] ?? null,
                    'tanggal_masuk'     => $parseDate($row[29]),
                    's_d'               => $parseDate($row[30]),
                    'masa_kerja'        => $row[31] ?? null,
                    'alamat'            => $row[32] ?? null,
                    'band'              => $row[33] ?? null,
                ]);
            }
            fclose($file);
        } else {
            $this->command->warn("File CSV tidak ditemukan.");
        }

        // 2. BUAT 5 DATA DUMMY (ULANG TAHUN HARI INI & ADA NO HP)
        // $this->command->info('Creating 5 Dummy Employees (Birthday Today)...');
        
        // for ($i = 1; $i <= 5; $i++) {
        //     TonasaEmployee::create([
        //         'sap_id'            => 'DUMMY00' . $i,
        //         'nik'               => 'TEST-' . $i,
        //         'nama'              => 'Karyawan Ultah ' . $i,
        //         'jabatan'           => 'Staff Percobaan',
        //         'unit_kerja'        => 'Departemen IT',
        //         'direktorat'        => 'Operasional',
        //         'tanggal_lahir'     => Carbon::now()->format('Y-m-d'), // Ulang Tahun Hari Ini
        //         'no_hp_1'           => '0811' . rand(1000, 9999) . '00' . $i, // Default
        //         'no_hp_2'           => '0812' . rand(1000, 9999) . '00' . $i,
        //         'no_hp_3'           => '0813' . rand(1000, 9999) . '00' . $i,
        //         'email'             => 'dummy'.$i.'@tonasa.co.id',
        //         'jenis_kelamin'     => ($i % 2 == 0) ? 'Perempuan' : 'Laki-laki',
        //         'umur'              => rand(25, 40),
        //         'tempat_lahir'      => 'Makassar',
        //         'alamat'            => 'Jl. Dummy No. ' . $i . ', Pangkep',
        //         'agama'             => 'Islam',
        //         'masa_kerja'        => rand(1, 10) . ' Tahun',
        //         'band'              => 'IV',
        //     ]);
        // }

        $this->command->info('All Done!');
    }
}