<?php

namespace App\Http\Controllers;

use App\Models\TonasaEmployee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployeeManagementController extends Controller
{
    // Menampilkan Tabel
    public function index(Request $request)
    {
        $query = TonasaEmployee::query();

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

    // Form Tambah
    public function create()
    {
        return view('admin.management.create');
    }

    // Simpan Data Baru (Store)
    public function store(Request $request)
    {
        // 1. VALIDASI DATA (Wajib Diisi & Format Benar)
        $request->validate([
            'nama'          => 'required|string|max:255',
            'nik'           => 'required|unique:tonasa_employees,nik', // NIK Wajib & Unik
            'sap_id'        => 'required|string|max:50',                // SAP Wajib
            'tanggal_lahir' => 'required|date',                         // Tanggal Lahir Wajib
            'tanggal_masuk' => 'required|date',                         // Tanggal Masuk Wajib
            'email'         => 'nullable|email',
        ], [
            // Pesan Error Custom
            'nama.required'          => 'Nama karyawan wajib diisi.',
            'nik.required'           => 'NIK wajib diisi.',
            'nik.unique'             => 'NIK sudah terdaftar di sistem.',
            'sap_id.required'        => 'SAP ID wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi untuk menghitung umur.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi untuk menghitung masa kerja.',
        ]);

        $data = $request->all();

        // 2. HITUNG UMUR (Otomatis)
        try {
            // Carbon::age otomatis membulatkan ke bawah (umur sebenarnya)
            $data['umur'] = Carbon::parse($request->tanggal_lahir)->age;
        } catch (\Exception $e) {
            $data['umur'] = 0; // Default jika error
        }

        // 3. HITUNG MASA KERJA (Otomatis & Dibulatkan)
        try {
            // Menggunakan (int) untuk memastikan angka bulat
            $data['masa_kerja'] = (int) Carbon::parse($request->tanggal_masuk)->diffInYears(Carbon::now());
        } catch (\Exception $e) {
            $data['masa_kerja'] = 0;
        }

        TonasaEmployee::create($data);

        return redirect()->route('employee-management.index')
            ->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    // Form Edit
    public function edit($id)
    {
        $employee = TonasaEmployee::findOrFail($id);
        return view('admin.management.edit', compact('employee'));
    }

    // Update Data
    public function update(Request $request, $id)
    {
        $employee = TonasaEmployee::findOrFail($id);

        // 1. VALIDASI DATA (Ignore NIK milik sendiri saat update)
        $request->validate([
            'nama'          => 'required|string|max:255',
            'nik'           => 'required|unique:tonasa_employees,nik,' . $id,
            'sap_id'        => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk' => 'required|date',
        ], [
            'nama.required'          => 'Nama karyawan wajib diisi.',
            'nik.required'           => 'NIK wajib diisi.',
            'nik.unique'             => 'NIK sudah digunakan karyawan lain.',
            'sap_id.required'        => 'SAP ID wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
        ]);

        $data = $request->all();

        // 2. HITUNG ULANG UMUR
        if ($request->filled('tanggal_lahir')) {
            try {
                $data['umur'] = Carbon::parse($request->tanggal_lahir)->age;
            } catch (\Exception $e) {
                // Biarkan nilai lama atau set null jika perlu
            }
        }

        // 3. HITUNG ULANG MASA KERJA
        if ($request->filled('tanggal_masuk')) {
            try {
                $data['masa_kerja'] = (int) Carbon::parse($request->tanggal_masuk)->diffInYears(Carbon::now());
            } catch (\Exception $e) {
                // Biarkan nilai lama
            }
        }

        $employee->update($data);

        return redirect()->route('employee-management.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    // Hapus Data
    public function destroy($id)
    {
        $employee = TonasaEmployee::findOrFail($id);
        $employee->delete();

        return redirect()->route('employee-management.index')
            ->with('success', 'Data karyawan berhasil dihapus.');
    }
}