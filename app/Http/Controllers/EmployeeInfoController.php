<?php

namespace App\Http\Controllers;

use App\Models\Employee; // PENTING: Gunakan Model Employee
use Illuminate\Http\Request;

class EmployeeInfoController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();

        // Fitur Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%")
                  ->orWhere('unit_kerja', 'LIKE', "%{$search}%")
                  ->orWhere('tkt_jabatan', 'LIKE', "%{$search}%");
            });
        }

        // Ambil data (10 per halaman)
        $employees = $query->latest()->paginate(10);

        return view('admin.employees.index', compact('employees'));
    }
}