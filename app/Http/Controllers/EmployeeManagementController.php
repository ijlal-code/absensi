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
        // 1. VALIDASI DATA
        $request->validate([
            'nama'          => 'required|string|max:255',
            'nik'           => 'required|unique:tonasa_employees,nik',
            'sap_id'        => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'email'         => 'nullable|email',
        ], [
            'nama.required'          => 'Nama karyawan wajib diisi.',
            'nik.required'           => 'NIK wajib diisi.',
            'nik.unique'             => 'NIK sudah terdaftar di sistem.',
            'sap_id.required'        => 'SAP ID wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
        ]);

        $data = $request->all();

        // 2. HITUNG UMUR
        if ($request->filled('tanggal_lahir')) {
            try {
                $data['umur'] = Carbon::parse($request->tanggal_lahir)->age;
            } catch (\Exception $e) {
                $data['umur'] = 0;
            }
        }

        // 3. HITUNG MASA KERJA (Otomatis Bulatkan ke Bawah)
        if ($request->filled('tanggal_masuk')) {
            try {
                // Carbon::diffInYears() secara default mengembalikan integer tahun penuh (floored)
                // Contoh: Masuk 2020-01-15, Sekarang 2025-01-14 = 4 Tahun (belum 5)
                $data['masa_kerja'] = (int) Carbon::parse($request->tanggal_masuk)->diffInYears(Carbon::now());
            } catch (\Exception $e) {
                $data['masa_kerja'] = 0;
            }
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

        if ($request->filled('tanggal_lahir')) {
            try {
                $data['umur'] = Carbon::parse($request->tanggal_lahir)->age;
            } catch (\Exception $e) {
                $data['umur'] = null;
            }
        }

        if ($request->filled('tanggal_masuk')) {
            try {
                // Hitung tahun penuh (round down)
                $data['masa_kerja'] = (int) Carbon::parse($request->tanggal_masuk)->diffInYears(Carbon::now());
            } catch (\Exception $e) {
                $data['masa_kerja'] = null;
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