<?php

namespace App\Http\Controllers;

use App\Models\TonasaEmployee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class EmployeeManagementController extends Controller
{
    /**
     * Menampilkan halaman statistik karyawan.
     */
    public function stats()
    {
        $employees = TonasaEmployee::all();

        // 1. Data Tingkat Pendidikan
        // Kita hitung dulu jumlahnya, urutan "Belum Diisi-SMA..." akan dihandle di JS
        $educationData = $employees->groupBy(function($item) {
            return $item->pendidikan ?? 'Belum Diisi';
        })->map->count();

        // 2. Data Masa Kerja (Diurutkan secara Logika Tahun)
        $serviceData = $employees->map(function($item) {
            $years = (int) $item->masa_kerja; 
            
            if ($years <= 5) return '0 - 5 Tahun'; 
            if ($years <= 10) return '6 - 10 Tahun';
            if ($years <= 20) return '11 - 20 Tahun';
            if ($years <= 25) return '21 - 25 Tahun';
            if ($years <= 30) return '26 - 30 Tahun';
            if ($years <= 35) return '31 - 35 Tahun';
            if ($years <= 40) return '36 - 40 Tahun';
            if ($years <= 45) return '41 - 45 Tahun';
            if ($years <= 50) return '46 - 50 Tahun';
            return '> 50 Tahun';
        })->groupBy(fn($item) => $item)->map->count();

        // URUTKAN KEY MASA KERJA (Agar defaultnya dari tahun terkecil)
        // Kita pakai array keys manual agar urutannya pasti
        $serviceOrder = [
            '0 - 5 Tahun', '6 - 10 Tahun', '11 - 20 Tahun', '21 - 25 Tahun',
            '26 - 30 Tahun', '31 - 35 Tahun', '36 - 40 Tahun', '41 - 45 Tahun', 
            '46 - 50 Tahun', '> 50 Tahun'
        ];
        $serviceData = $serviceData->sortBy(function($val, $key) use ($serviceOrder) {
            return array_search($key, $serviceOrder);
        });

        // 3. Data Tingkat Usia (Diurutkan secara Logika Umur)
        $ageData = $employees->map(function($item) {
            $age = (int) $item->umur;
            if ($age < 25) return '< 25 Tahun';
            if ($age <= 35) return '25 - 35 Tahun';
            if ($age <= 45) return '36 - 45 Tahun';
            if ($age <= 55) return '46 - 55 Tahun';
            return '> 55 Tahun';
        })->groupBy(fn($item) => $item)->map->count();

        // URUTKAN KEY USIA
        $ageOrder = ['< 25 Tahun', '25 - 35 Tahun', '36 - 45 Tahun', '46 - 55 Tahun', '> 55 Tahun'];
        $ageData = $ageData->sortBy(function($val, $key) use ($ageOrder) {
            return array_search($key, $ageOrder);
        });

        // 4. Data Jenis Kelamin
        $genderData = $employees->groupBy(function($item) {
            return $item->jenis_kelamin ?? 'Tidak Diketahui';
        })->map->count();
        // Gender akan diurutkan by Value (jumlah) di Frontend sesuai request

        return view('admin.management.stats', compact('educationData', 'serviceData', 'ageData', 'genderData'));
    }

    /**
     * Menampilkan daftar karyawan dengan pencarian.
     */
    public function index(Request $request)
    {
        $query = TonasaEmployee::query();

        // Pencarian (Nama, NIK, SAP ID)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%")
                  ->orWhere('sap_id', 'LIKE', "%{$search}%");
            });
        }

        $employees = $query->latest()->paginate(10);
        return view('admin.management.index', compact('employees'));
    }

    /**
     * Menampilkan form tambah data.
     */
    public function create()
    {
        return view('admin.management.create');
    }

    /**
     * Menyimpan data karyawan baru.
     */
    public function store(Request $request)
    {
        // 1. VALIDASI INPUT
        $request->validate([
            'nama'          => 'required|string|max:255',
            'nik'           => 'required|unique:tonasa_employees,nik',
            'sap_id'        => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'pendidikan'    => 'nullable|string',
            'email'         => 'nullable|email',
            'foto_terbaru'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_lama'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Validasi Kontak & Primary Phone
            'no_hp_1'       => 'nullable|string',
            'no_hp_2'       => 'nullable|string',
            'no_hp_3'       => 'nullable|string',
            'primary_phone' => 'required|in:no_hp_1,no_hp_2,no_hp_3',
        ], [
            'nik.required'           => 'NIK wajib diisi.',
            'nik.unique'             => 'NIK sudah terdaftar di sistem.',
            'sap_id.required'        => 'SAP ID wajib diisi.',
            'nama.required'          => 'Nama lengkap wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'primary_phone.required' => 'Silakan pilih satu nomor HP sebagai nomor utama.',
            'primary_phone.in'       => 'Pilihan nomor utama tidak valid.',
        ]);

        $data = $request->all();

        // 2. HITUNG UMUR
        try {
            $data['umur'] = Carbon::parse($request->tanggal_lahir)->age;
        } catch (\Exception $e) {
            $data['umur'] = 0;
        }

        // 3. HITUNG MASA KERJA
        try {
            $data['masa_kerja'] = (int) Carbon::parse($request->tanggal_masuk)->diffInYears(Carbon::now());
        } catch (\Exception $e) {
            $data['masa_kerja'] = 0;
        }

        // 4. UPLOAD FOTO
        if ($request->hasFile('foto_terbaru')) {
            $data['foto_terbaru'] = $request->file('foto_terbaru')->store('employees/new', 'public');
        }

        if ($request->hasFile('foto_lama')) {
            $data['foto_lama'] = $request->file('foto_lama')->store('employees/old', 'public');
        }

        TonasaEmployee::create($data);

        return redirect()->route('employee-management.index')
            ->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail karyawan (Untuk Modal/Popup).
     */
    public function show($id)
    {
        $employee = TonasaEmployee::findOrFail($id);
        
        // Jika request datang dari AJAX (untuk modal), return partial view atau JSON
        if (request()->ajax()) {
            return view('admin.show_partial', compact('employee'))->render();
        }
        
        return view('admin.management.show', compact('employee'));
    }

    /**
     * Menampilkan form edit data.
     */
    public function edit($id)
    {
        $employee = TonasaEmployee::findOrFail($id);
        return view('admin.management.edit', compact('employee'));
    }

    /**
     * Memperbarui data karyawan.
     */
    public function update(Request $request, $id)
    {
        $employee = TonasaEmployee::findOrFail($id);

        $request->validate([
            'nama'          => 'required|string|max:255',
            'nik'           => 'required|unique:tonasa_employees,nik,' . $id,
            'sap_id'        => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'pendidikan'    => 'nullable|string',
            'email'         => 'nullable|email',
            'foto_terbaru'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_lama'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            
            // Validasi Kontak
            'no_hp_1'       => 'nullable|string',
            'no_hp_2'       => 'nullable|string',
            'no_hp_3'       => 'nullable|string',
            'primary_phone' => 'required|in:no_hp_1,no_hp_2,no_hp_3',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique'   => 'NIK sudah digunakan karyawan lain.',
            'primary_phone.required' => 'Silakan pilih satu nomor HP sebagai nomor utama.',
        ]);

        $data = $request->all();

        // Hitung ulang umur jika tanggal lahir berubah
        if ($request->filled('tanggal_lahir')) {
            try {
                $data['umur'] = Carbon::parse($request->tanggal_lahir)->age;
            } catch (\Exception $e) {}
        }

        // Hitung ulang masa kerja jika tanggal masuk berubah
        if ($request->filled('tanggal_masuk')) {
            try {
                $data['masa_kerja'] = (int) Carbon::parse($request->tanggal_masuk)->diffInYears(Carbon::now());
            } catch (\Exception $e) {}
        }

        // Update Foto Terbaru
        if ($request->hasFile('foto_terbaru')) {
            if ($employee->foto_terbaru && Storage::disk('public')->exists($employee->foto_terbaru)) {
                Storage::disk('public')->delete($employee->foto_terbaru);
            }
            $data['foto_terbaru'] = $request->file('foto_terbaru')->store('employees/new', 'public');
        }

        // Update Foto Lama
        if ($request->hasFile('foto_lama')) {
            if ($employee->foto_lama && Storage::disk('public')->exists($employee->foto_lama)) {
                Storage::disk('public')->delete($employee->foto_lama);
            }
            $data['foto_lama'] = $request->file('foto_lama')->store('employees/old', 'public');
        }

        $employee->update($data);

        return redirect()->route('employee-management.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Menghapus data karyawan.
     */
    public function destroy($id)
    {
        $employee = TonasaEmployee::findOrFail($id);

        if ($employee->foto_terbaru && Storage::disk('public')->exists($employee->foto_terbaru)) {
            Storage::disk('public')->delete($employee->foto_terbaru);
        }

        if ($employee->foto_lama && Storage::disk('public')->exists($employee->foto_lama)) {
            Storage::disk('public')->delete($employee->foto_lama);
        }

        $employee->delete();

        return redirect()->route('employee-management.index')
            ->with('success', 'Data karyawan berhasil dihapus.');
    }
}