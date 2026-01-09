<?php

namespace App\Http\Controllers;

use App\Models\TonasaEmployee; // PENTING: Pakai Model Baru
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployeeInfoController extends Controller
{
    public function index(Request $request)
    {
        $query = TonasaEmployee::query();

        // 1. Fitur Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%")
                  ->orWhere('jabatan', 'LIKE', "%{$search}%")
                  ->orWhere('unit_kerja', 'LIKE', "%{$search}%");
            });
        }

        // 2. Fitur Filter Ulang Tahun
        if ($request->has('filter_birthday') && $request->filter_birthday == 'today') {
            $query->whereMonth('tanggal_lahir', Carbon::now()->month)
                  ->whereDay('tanggal_lahir', Carbon::now()->day);
        }

        $employees = $query->paginate(10);

        return view('admin.employees.index', compact('employees'));
    }
}