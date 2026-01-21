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

   
}