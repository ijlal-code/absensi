<?php

namespace App\Http\Controllers;

use App\Models\TonasaEmployee;
use Illuminate\Http\Request;
use Carbon\Carbon; // Pastikan library Carbon di-load

class EmployeeInfoController extends Controller
{
    /**
     * Menampilkan daftar karyawan.
     */
    public function index(Request $request)
    {
        $query = TonasaEmployee::query();

        // Fitur Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%")
                  ->orWhere('sap_id', 'LIKE', "%{$search}%")
                  ->orWhere('jabatan', 'LIKE', "%{$search}%")
                  ->orWhere('unit_kerja', 'LIKE', "%{$search}%");
            });
        }

        // Fitur Filter Ulang Tahun Hari Ini
        if ($request->has('filter_birthday') && $request->filter_birthday == 'today') {
            $query->whereMonth('tanggal_lahir', Carbon::now()->month)
                  ->whereDay('tanggal_lahir', Carbon::now()->day);
        }

        $employees = $query->latest()->paginate(10);
        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Menampilkan form tambah karyawan baru.
     */
    public function create()
    {
        return view('admin.employees.create');
    }

    /**
     * Menyimpan data karyawan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'nullable|unique:tonasa_employees,nik',
            'email' => 'nullable|email',
        ]);

        // Ambil semua input
        $data = $request->all();

        // 1. HITUNG UMUR OTOMATIS
        if ($request->filled('tanggal_lahir')) {
            try {
                $data['umur'] = Carbon::parse($request->tanggal_lahir)->age;
            } catch (\Exception $e) {
                $data['umur'] = null;
            }
        }

        // 2. HITUNG MASA KERJA OTOMATIS
        if ($request->filled('tanggal_masuk')) {
            try {
                $data['masa_kerja'] = Carbon::parse($request->tanggal_masuk)->diffInYears(Carbon::now());
            } catch (\Exception $e) {
                $data['masa_kerja'] = null;
            }
        }

        TonasaEmployee::create($data);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit data karyawan.
     */
    public function edit($id)
    {
        $employee = TonasaEmployee::findOrFail($id);
        return view('admin.employees.edit', compact('employee'));
    }

    /**
     * Memperbarui data karyawan di database.
     */
    public function update(Request $request, $id)
    {
        $employee = TonasaEmployee::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'nullable|unique:tonasa_employees,nik,' . $id,
            'email' => 'nullable|email',
        ]);

        // Ambil semua input
        $data = $request->all();

        // 1. HITUNG ULANG UMUR (Jika berubah)
        if ($request->filled('tanggal_lahir')) {
            try {
                $data['umur'] = Carbon::parse($request->tanggal_lahir)->age;
            } catch (\Exception $e) {
                $data['umur'] = null;
            }
        }

        // 2. HITUNG ULANG MASA KERJA (Jika berubah)
        if ($request->filled('tanggal_masuk')) {
            try {
                $data['masa_kerja'] = Carbon::parse($request->tanggal_masuk)->diffInYears(Carbon::now());
            } catch (\Exception $e) {
                $data['masa_kerja'] = null;
            }
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Menghapus data karyawan.
     */
    public function destroy($id)
    {
        $employee = TonasaEmployee::findOrFail($id);
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil dihapus.');
    }
}