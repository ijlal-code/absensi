<?php

namespace App\Http\Controllers;

use App\Models\TonasaEmployee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage; // Penting untuk hapus/upload foto

class EmployeeInfoController extends Controller
{
    /**
     * Menampilkan daftar karyawan dengan fitur pencarian & filter.
     */
    public function index(Request $request)
    {
        $query = TonasaEmployee::query();

        // 1. LOGIKA PENCARIAN (Hanya Nama, NIK, SAP ID)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%")
                  ->orWhere('sap_id', 'LIKE', "%{$search}%");
            });
        }

        // 2. FILTER ULANG TAHUN HARI INI
        if ($request->has('filter_birthday') && $request->filter_birthday == 'today') {
            $query->whereMonth('tanggal_lahir', Carbon::now()->month)
                  ->whereDay('tanggal_lahir', Carbon::now()->day);
        }

        $employees = $query->latest()->paginate(10);
        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Menampilkan form tambah karyawan.
     */
    public function create()
    {
        return view('admin.employees.create');
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
            'email'         => 'nullable|email',
            'foto_terbaru'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_lama'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nik.required'           => 'NIK wajib diisi.',
            'nik.unique'             => 'NIK sudah terdaftar.',
            'sap_id.required'        => 'SAP ID wajib diisi.',
            'nama.required'          => 'Nama wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
        ]);

        $data = $request->all();

        // 2. HITUNG UMUR (Otomatis)
        if ($request->filled('tanggal_lahir')) {
            try {
                $data['umur'] = Carbon::parse($request->tanggal_lahir)->age;
            } catch (\Exception $e) {
                $data['umur'] = 0;
            }
        }

        // 3. HITUNG MASA KERJA (Bulat ke bawah / Tahun Penuh)
        if ($request->filled('tanggal_masuk')) {
            try {
                $data['masa_kerja'] = (int) Carbon::parse($request->tanggal_masuk)->diffInYears(Carbon::now());
            } catch (\Exception $e) {
                $data['masa_kerja'] = 0;
            }
        }

        // 4. UPLOAD FOTO
        if ($request->hasFile('foto_terbaru')) {
            $data['foto_terbaru'] = $request->file('foto_terbaru')->store('employees/new', 'public');
        }
        if ($request->hasFile('foto_lama')) {
            $data['foto_lama'] = $request->file('foto_lama')->store('employees/old', 'public');
        }

        TonasaEmployee::create($data);

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit.
     */
    public function edit($id)
    {
        $employee = TonasaEmployee::findOrFail($id);
        return view('admin.employees.edit', compact('employee'));
    }

    /**
     * Memperbarui data karyawan.
     */
    public function update(Request $request, $id)
    {
        $employee = TonasaEmployee::findOrFail($id);

        // 1. VALIDASI
        $request->validate([
            'nama'          => 'required|string|max:255',
            'nik'           => 'required|unique:tonasa_employees,nik,' . $id,
            'sap_id'        => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'email'         => 'nullable|email',
            'foto_terbaru'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_lama'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'sap_id.required' => 'SAP ID wajib diisi.',
            'nama.required' => 'Nama wajib diisi.',
        ]);

        $data = $request->all();

        // 2. HITUNG ULANG UMUR
        if ($request->filled('tanggal_lahir')) {
            try {
                $data['umur'] = Carbon::parse($request->tanggal_lahir)->age;
            } catch (\Exception $e) {}
        }

        // 3. HITUNG ULANG MASA KERJA (Bulat ke bawah)
        if ($request->filled('tanggal_masuk')) {
            try {
                $data['masa_kerja'] = (int) Carbon::parse($request->tanggal_masuk)->diffInYears(Carbon::now());
            } catch (\Exception $e) {}
        }

        // 4. UPDATE FOTO (Hapus lama jika ada baru)
        if ($request->hasFile('foto_terbaru')) {
            if ($employee->foto_terbaru && Storage::disk('public')->exists($employee->foto_terbaru)) {
                Storage::disk('public')->delete($employee->foto_terbaru);
            }
            $data['foto_terbaru'] = $request->file('foto_terbaru')->store('employees/new', 'public');
        }

        if ($request->hasFile('foto_lama')) {
            if ($employee->foto_lama && Storage::disk('public')->exists($employee->foto_lama)) {
                Storage::disk('public')->delete($employee->foto_lama);
            }
            $data['foto_lama'] = $request->file('foto_lama')->store('employees/old', 'public');
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

        // Hapus file fisik foto saat data dihapus
        if ($employee->foto_terbaru && Storage::disk('public')->exists($employee->foto_terbaru)) {
            Storage::disk('public')->delete($employee->foto_terbaru);
        }
        if ($employee->foto_lama && Storage::disk('public')->exists($employee->foto_lama)) {
            Storage::disk('public')->delete($employee->foto_lama);
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil dihapus.');
    }
}