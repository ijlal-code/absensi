<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TonasaEmployee;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TonasaImportSeeder extends Seeder
{
    public function run()
    {
        // Pastikan file CSV ada di folder root project Anda
        $csvPath = base_path('DATABASE KARYAWAN SEMEN TONASA 2026.xlsx - Sheet1.csv'); 

        if (!file_exists($csvPath)) {
            $this->command->error("File CSV tidak ditemukan di: $csvPath");
            return;
        }

        // Kosongkan tabel sebelum import ulang
        TonasaEmployee::truncate();

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file); // Skip baris header

        $this->command->info('Memulai import data karyawan Tonasa...');

        while (($row = fgetcsv($file)) !== false) {
            // Validasi: Lewati jika Nama kosong
            if (empty($row[3])) continue;

            // Helper function untuk parsing tanggal
            $parseDate = function($dateString) {
                if (empty($dateString)) return null;
                try {
                    // Coba format YYYY-MM-DD (Default database/Excel export)
                    return Carbon::parse($dateString)->format('Y-m-d');
                } catch (\Exception $e) {
                    try {
                        // Coba format MM/DD/YYYY
                        return Carbon::createFromFormat('m/d/Y', $dateString)->format('Y-m-d');
                    } catch (\Exception $ex) {
                        return null;
                    }
                }
            };

            TonasaEmployee::create([
                // Data Umum
                'sap_id'        => $row[0] ?? null,
                'nik'           => $row[1] ?? null,
                'nama'          => $row[3], 
                'jabatan'       => $row[4] ?? '-',     // Position (Text)
                'unit_kerja'    => $row[9] ?? '-',     // TXT_BIRO
                'departemen'    => $row[8] ?? '-',     // TXT_DEPT
                
                // Data Detail (Sensitif)
                'tanggal_lahir'   => $parseDate($row[11]), // Birth date
                'jenis_kelamin'   => $row[12] ?? null,
                'tanggal_pensiun' => $parseDate($row[22]), // Date Terminasi
                'email'           => $row[23] ?? null,
                'agama'           => $row[24] ?? null,
                'tempat_lahir'    => $row[27] ?? null,
                'pendidikan'      => $row[28] ?? null,
                'tanggal_masuk'   => $parseDate($row[29]), // Organilk
                'alamat'          => $row[32] ?? null,
                'band'            => $row[33] ?? null,
            ]);
        }

        fclose($file);
        $this->command->info('Import selesai!');
    }
}