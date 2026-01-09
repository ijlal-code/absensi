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

        if (!file_exists($csvPath)) {
            $this->command->error("File CSV tidak ditemukan.");
            return;
        }

        TonasaEmployee::truncate();
        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file); // Skip Header

        $this->command->info('Importing data...');

        while (($row = fgetcsv($file)) !== false) {
            if (empty($row[3])) continue; // Skip jika Nama kosong

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
                'position_code'     => $row[2] ?? null, // Position (Angka)
                'nama'              => $row[3], 
                'jabatan'           => $row[4] ?? null, // Position (Teks)
                'subgroup'          => $row[5] ?? null,
                'cost_ctr'          => $row[6] ?? null,
                'direktorat'        => $row[7] ?? null,
                'departemen'        => $row[8] ?? null,
                'unit_kerja'        => $row[9] ?? null,  // TXT_BIRO
                'seksi'             => $row[10] ?? null, // TXT_SECT
                
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
                
                'tanggal_pensiun'   => $parseDate($row[22]),
                'email'             => $row[23] ?? null,
                'agama'             => $row[24] ?? null,
                'umur'              => $row[26] ?? null,
                'tempat_lahir'      => $row[27] ?? null,
                'pendidikan'        => $row[28] ?? null,
                'tanggal_masuk'     => $parseDate($row[29]),
                'masa_kerja'        => $row[31] ?? null,
                'alamat'            => $row[32] ?? null,
                'band'              => $row[33] ?? null,
            ]);
        }
        fclose($file);
        $this->command->info('Import Done!');
    }
}