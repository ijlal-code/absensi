<?php

namespace App\Http\Controllers;

use App\Models\TonasaEmployee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage; // Wajib untuk menangani file foto

class EmployeeManagementController extends Controller
{
    /**
     * Menampilkan daftar karyawan dengan pencarian spesifik.
     */
    public function index(Request $request)
    {
        $query = TonasaEmployee::query();

        // LOGIKA PENCARIAN (Hanya Nama, NIK, SAP ID)
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
     * Menyimpan data karyawan baru beserta foto.
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
            // Validasi Foto: Harus gambar, max 2MB
            'foto_terbaru'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_lama'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nik.required'           => 'NIK wajib diisi.',
            'nik.unique'             => 'NIK sudah terdaftar di sistem.',
            'sap_id.required'        => 'SAP ID wajib diisi.',
            'nama.required'          => 'Nama lengkap wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'foto_terbaru.image'     => 'File foto terbaru harus berupa gambar.',
            'foto_terbaru.max'       => 'Ukuran foto terbaru maksimal 2MB.',
        ]);

        $data = $request->all();

        // 2. HITUNG UMUR (Otomatis)
        try {
            $data['umur'] = Carbon::parse($request->tanggal_lahir)->age;
        } catch (\Exception $e) {
            $data['umur'] = 0;
        }

        // 3. HITUNG MASA KERJA (Bulat ke Bawah / Tahun Penuh)
        try {
            $data['masa_kerja'] = (int) Carbon::parse($request->tanggal_masuk)->diffInYears(Carbon::now());
        } catch (\Exception $e) {
            $data['masa_kerja'] = 0;
        }

        // 4. UPLOAD FOTO
        if ($request->hasFile('foto_terbaru')) {
            // Simpan di folder public/employees/new
            $data['foto_terbaru'] = $request->file('foto_terbaru')->store('employees/new', 'public');
        }

        if ($request->hasFile('foto_lama')) {
            // Simpan di folder public/employees/old
            $data['foto_lama'] = $request->file('foto_lama')->store('employees/old', 'public');
        }

        TonasaEmployee::create($data);

        return redirect()->route('employee-management.index')
            ->with('success', 'Data karyawan berhasil ditambahkan.');
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

        // 1. VALIDASI (Abaikan unique NIK milik sendiri)
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
            'nik.unique'   => 'NIK sudah digunakan karyawan lain.',
        ]);

        $data = $request->all();

        // 2. HITUNG ULANG UMUR (Jika tanggal berubah)
        if ($request->filled('tanggal_lahir')) {
            try {
                $data['umur'] = Carbon::parse($request->tanggal_lahir)->age;
            } catch (\Exception $e) {}
        }

        // 3. HITUNG ULANG MASA KERJA (Jika tanggal berubah)
        if ($request->filled('tanggal_masuk')) {
            try {
                $data['masa_kerja'] = (int) Carbon::parse($request->tanggal_masuk)->diffInYears(Carbon::now());
            } catch (\Exception $e) {}
        }

        // 4. UPDATE FOTO TERBARU
        if ($request->hasFile('foto_terbaru')) {
            // Hapus file lama jika ada
            if ($employee->foto_terbaru && Storage::disk('public')->exists($employee->foto_terbaru)) {
                Storage::disk('public')->delete($employee->foto_terbaru);
            }
            // Upload file baru
            $data['foto_terbaru'] = $request->file('foto_terbaru')->store('employees/new', 'public');
        }

        // 5. UPDATE FOTO LAMA
        if ($request->hasFile('foto_lama')) {
            // Hapus file lama jika ada
            if ($employee->foto_lama && Storage::disk('public')->exists($employee->foto_lama)) {
                Storage::disk('public')->delete($employee->foto_lama);
            }
            // Upload file baru
            $data['foto_lama'] = $request->file('foto_lama')->store('employees/old', 'public');
        }

        $employee->update($data);

        return redirect()->route('employee-management.index')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Menghapus data karyawan beserta fotonya.
     */
    public function destroy($id)
    {
        $employee = TonasaEmployee::findOrFail($id);

        // Hapus file fisik foto dari storage
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