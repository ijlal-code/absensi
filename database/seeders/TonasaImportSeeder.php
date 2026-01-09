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
        // 1. Pastikan nama file CSV sesuai dengan yang ada di folder project Anda
        $csvPath = base_path('DATABASE KARYAWAN SEMEN TONASA 2026.xlsx - Sheet1.csv'); 

        if (!file_exists($csvPath)) {
            $this->command->error("File CSV tidak ditemukan di: $csvPath");
            return;
        }

        // 2. Kosongkan tabel dulu
        TonasaEmployee::truncate();

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file); // Lewati baris judul (header)

        $this->command->info('Memulai import data karyawan Tonasa...');

        while (($row = fgetcsv($file)) !== false) {
            // Lewati jika Nama kosong (Baris kosong di Excel)
            if (empty($row[3])) continue;

            // --- PERBAIKAN FORMAT TANGGAL ---
            // Fungsi untuk mengubah '11/15/1969' menjadi '1969-11-15'
            $parseDate = function($dateString) {
                if (empty($dateString)) return null;
                try {
                    return Carbon::createFromFormat('m/d/Y', $dateString)->format('Y-m-d');
                } catch (\Exception $e) {
                    try {
                        return Carbon::parse($dateString)->format('Y-m-d');
                    } catch (\Exception $ex) {
                        return null;
                    }
                }
            };

            TonasaEmployee::create([
                // DATA UMUM
                'sap_id'        => $row[0] ?? null,
                'nik'           => $row[1] ?? null,
                'nama'          => $row[3], 
                'jabatan'       => $row[4] ?? '-',     // Ambil Kolom 4 (Teks Jabatan)
                'unit_kerja'    => $row[9] ?? '-',     // TXT_BIRO
                'departemen'    => $row[8] ?? '-',     // TXT_DEPT
                
                // DATA DETAIL (SENSITIF)
                'tanggal_lahir'   => $parseDate($row[11]),
                'jenis_kelamin'   => $row[12] ?? null,
                'tanggal_pensiun' => $parseDate($row[22]),
                'email'           => $row[23] ?? null,
                'agama'           => $row[24] ?? null,
                'tempat_lahir'    => $row[27] ?? null,
                'pendidikan'      => $row[28] ?? null,
                'tanggal_masuk'   => $parseDate($row[29]),
                'alamat'          => $row[32] ?? null,
                'band'            => $row[33] ?? null,
            ]);
        }

        fclose($file);
        $this->command->info('Import selesai!');
    }
}