<?php

namespace App\Http\Controllers;

use App\Models\TonasaEmployee;
use Illuminate\Http\Request;

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

    // Simpan Data
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'nullable|unique:tonasa_employees,nik',
        ]);

        TonasaEmployee::create($request->all());

        return redirect()->route('employee-management.index')
            ->with('success', 'Data karyawan berhasil ditambahkan.');
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

        $employee->update($request->all());

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