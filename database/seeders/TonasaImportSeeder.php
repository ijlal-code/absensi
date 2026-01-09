<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TonasaEmployee;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // PENTING: Tambahkan ini

class TonasaImportSeeder extends Seeder
{
    public function run()
    {
        // 1. Sesuaikan path file Anda (gunakan storage_path jika file ada di storage/app)
        // Jika file ada di folder root project (sejajar .env):
        $csvFile = base_path('DATABASE KARYAWAN SEMEN TONASA 2026.xlsx - Sheet1.csv'); 

        // Jika file ada di storage/app/karyawan.csv (sesuai saran sebelumnya):
        // $csvFile = storage_path('app/karyawan.csv'); 

        if (!file_exists($csvFile)) {
            $this->command->error("File CSV tidak ditemukan di: $csvFile");
            return;
        }

        $data = array_map('str_getcsv', file($csvFile));
        $header = array_shift($data); 

        // Mapping Index
        $idx_nik = array_search('NIK', $header);
        $idx_sap = array_search('SAP', $header);
        $idx_nama = array_search('NAMA KARYAWAN', $header);
        
        $keys_position = array_keys($header, 'Position');
        $idx_jabatan = $keys_position[1] ?? $keys_position[0]; 

        $idx_unit = array_search('TXT_BIRO', $header); 
        $idx_tgl_lahir = array_search('Birth date', $header);
        $idx_gender = array_search('Gender Key', $header);
        $idx_agama = array_search('Religious', $header);
        $idx_alamat = array_search('Alamat', $header);
        $idx_email = array_search('E-mail', $header);
        $idx_tempat_lahir = array_search('Tempat Lahir', $header);
        $idx_pendidikan = array_search('Pendidikan', $header);

        $this->command->info('Memulai import data karyawan Tonasa...');

        foreach ($data as $row) {
            // Lewati jika NIK kosong
            if (empty($row[$idx_nik])) continue;

            // --- PERBAIKAN FORMAT TANGGAL ---
            $rawDate = $row[$idx_tgl_lahir] ?? null;
            $finalDate = null;

            if (!empty($rawDate)) {
                try {
                    // Coba format m/d/Y (Contoh: 11/15/1969 sesuai error Anda)
                    $finalDate = Carbon::createFromFormat('m/d/Y', $rawDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    try {
                        // Jika gagal, coba format Y-m-d (siapa tahu ada baris yang formatnya benar)
                        $finalDate = Carbon::parse($rawDate)->format('Y-m-d');
                    } catch (\Exception $ex) {
                        // Jika masih gagal, biarkan NULL agar tidak error
                        $finalDate = null;
                    }
                }
            }
            // --------------------------------

            TonasaEmployee::updateOrCreate(
                ['nik' => $row[$idx_nik]], 
                [
                    'sap_id' => $row[$idx_sap] ?? null,
                    'nama' => $row[$idx_nama],
                    'jabatan' => $row[$idx_jabatan] ?? '-',
                    'unit_kerja' => $row[$idx_unit] ?? '-',
                    
                    // Masukkan tanggal yang sudah diperbaiki formatnya
                    'tanggal_lahir' => $finalDate,
                    
                    'jenis_kelamin' => $row[$idx_gender] ?? null,
                    'agama' => $row[$idx_agama] ?? null,
                    'alamat' => $row[$idx_alamat] ?? null,
                    'email' => $row[$idx_email] ?? null,
                    'tempat_lahir' => $row[$idx_tempat_lahir] ?? null,
                    'pendidikan' => $row[$idx_pendidikan] ?? null,
                ]
            );
        }
        
        $this->command->info('Import selesai!');
    }
}