<?php

namespace App\Http\Controllers;

use App\Models\TonasaEmployee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployeeInfoController extends Controller
{
    // Menampilkan Daftar Karyawan
    public function index(Request $request)
    {
        $query = TonasaEmployee::query();

        // Search Logic
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

        // Filter Ulang Tahun
        if ($request->has('filter_birthday') && $request->filter_birthday == 'today') {
            $query->whereMonth('tanggal_lahir', Carbon::now()->month)
                  ->whereDay('tanggal_lahir', Carbon::now()->day);
        }

        $employees = $query->latest()->paginate(10);
        return view('admin.employees.index', compact('employees'));
    }

    // Form Tambah
    public function create()
    {
        return view('admin.employees.create');
    }

    // Simpan Data
    public function store(Request $request)
    {
        // 1. VALIDASI KETAT
        $request->validate([
            'nama'          => 'required|string|max:255',
            'nik'           => 'required|unique:tonasa_employees,nik',
            'sap_id'        => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'email'         => 'nullable|email',
        ], [
            'nama.required'          => 'Nama wajib diisi.',
            'nik.required'           => 'NIK wajib diisi.',
            'nik.unique'             => 'NIK sudah ada.',
            'sap_id.required'        => 'SAP ID wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal Lahir wajib diisi.',
            'tanggal_masuk.required' => 'Tanggal Masuk wajib diisi.',
        ]);

        $data = $request->all();

        // 2. HITUNG OTOMATIS
        try {
            $data['umur'] = Carbon::parse($request->tanggal_lahir)->age;
        } catch (\Exception $e) {
            $data['umur'] = 0;
        }

        try {
            $data['masa_kerja'] = (int) Carbon::parse($request->tanggal_masuk)->diffInYears(Carbon::now());
        } catch (\Exception $e) {
            $data['masa_kerja'] = 0;
        }

        TonasaEmployee::create($data);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    // Form Edit
    public function edit($id)
    {
        $employee = TonasaEmployee::findOrFail($id);
        return view('admin.employees.edit', compact('employee'));
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
            'email'         => 'nullable|email',
        ]);

        $data = $request->all();

        // Hitung Ulang jika tanggal diubah
        if ($request->filled('tanggal_lahir')) {
            try {
                $data['umur'] = Carbon::parse($request->tanggal_lahir)->age;
            } catch (\Exception $e) {}
        }

        if ($request->filled('tanggal_masuk')) {
            try {
                $data['masa_kerja'] = (int) Carbon::parse($request->tanggal_masuk)->diffInYears(Carbon::now());
            } catch (\Exception $e) {}
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    // Hapus Data
    public function destroy($id)
    {
        $employee = TonasaEmployee::findOrFail($id);
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil dihapus.');
    }
}