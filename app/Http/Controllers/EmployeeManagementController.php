<?php

namespace App\Http\Controllers;

use App\Models\TonasaEmployee;
use Illuminate\Http\Request;
use Carbon\Carbon; // Tambahkan library Carbon untuk menghitung tanggal

class EmployeeManagementController extends Controller
{
    // Menampilkan Tabel Ringkas (Hanya NIK & Nama)
    public function index(Request $request)
    {
        $query = TonasaEmployee::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%");
            });
        }

        $employees = $query->latest()->paginate(10);
        return view('admin.management.index', compact('employees'));
    }

    // Form Tambah (Full Field)
    public function create()
    {
        return view('admin.management.create');
    }

    // Simpan Data (Store)
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'nullable|unique:tonasa_employees,nik',
            // Tambahkan validasi tanggal jika perlu, misal: 'tanggal_lahir' => 'nullable|date'
        ]);

        // Ambil semua data inputan form
        $data = $request->all();

        // 1. HITUNG UMUR OTOMATIS (Jika Tanggal Lahir diisi)
        if ($request->filled('tanggal_lahir')) {
            try {
                $data['umur'] = Carbon::parse($request->tanggal_lahir)->age;
            } catch (\Exception $e) {
                $data['umur'] = null;
            }
        }

        // 2. HITUNG MASA KERJA OTOMATIS (Jika Tanggal Masuk diisi)
        if ($request->filled('tanggal_masuk')) {
            try {
                $data['masa_kerja'] = Carbon::parse($request->tanggal_masuk)->diffInYears(Carbon::now());
            } catch (\Exception $e) {
                $data['masa_kerja'] = null;
            }
        }

        // Simpan data (menggunakan variable $data yang sudah dimodifikasi)
        TonasaEmployee::create($data);

        return redirect()->route('employee-management.index')
            ->with('success', 'Data karyawan berhasil ditambahkan (Umur & Masa Kerja otomatis dihitung).');
    }

    // Form Edit (Full Field)
    public function edit($id)
    {
        $employee = TonasaEmployee::findOrFail($id);
        return view('admin.management.edit', compact('employee'));
    }

    // Update Data
    public function update(Request $request, $id)
    {
        $employee = TonasaEmployee::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'nullable|unique:tonasa_employees,nik,' . $id,
        ]);

        // Ambil semua data inputan form
        $data = $request->all();

        // 1. HITUNG ULANG UMUR (Jika Tanggal Lahir diubah/ada)
        if ($request->filled('tanggal_lahir')) {
            try {
                $data['umur'] = Carbon::parse($request->tanggal_lahir)->age;
            } catch (\Exception $e) {
                $data['umur'] = null;
            }
        }

        // 2. HITUNG ULANG MASA KERJA (Jika Tanggal Masuk diubah/ada)
        if ($request->filled('tanggal_masuk')) {
            try {
                $data['masa_kerja'] = Carbon::parse($request->tanggal_masuk)->diffInYears(Carbon::now());
            } catch (\Exception $e) {
                $data['masa_kerja'] = null;
            }
        }

        // Update data menggunakan array $data
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