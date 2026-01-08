<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmployeeInfoController extends Controller
{
    public function index(Request $request)
    {
        // Ambil query dasar user (bisa difilter misal hanya role 'karyawan' jika perlu)
        $query = User::query();

        // Logika Pencarian (Search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%")
                  ->orWhere('unit_kerja', 'LIKE', "%{$search}%");
            });
        }

        // Ambil data dengan pagination (10 per halaman)
        $employees = $query->latest()->paginate(10);

        return view('admin.employees.index', compact('employees'));
    }
}