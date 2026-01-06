<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    // 1. Dashboard Utama (Ringkasan)
    public function index()
    {
        $todayEvents = Event::whereDate('date', Carbon::today())->get();
        $totalEvents = Event::count();
        return view('admin.dashboard', compact('todayEvents', 'totalEvents'));
    }

    // 2. Halaman Buat Absensi
    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'location' => 'required',
        ]);

        Event::create($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Jadwal Absensi Berhasil Dibuat!');
    }

    // 3. Halaman Monitor Absensi (Lihat data peserta)
    public function show(Event $event)
    {
        $attendances = $event->attendances()->latest()->get();
        return view('admin.show', compact('event', 'attendances'));
    }

    // 4. Halaman Laporan (Arsip & Download)
    public function reports()
    {
        // Mengambil semua event diurutkan dari yang terbaru
        $events = Event::withCount('attendances')->latest('date')->get();
        return view('admin.reports', compact('events'));
    }
}