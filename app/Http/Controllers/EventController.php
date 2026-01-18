<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TonasaEmployee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth; // PENTING: Untuk ambil Auth::id()

class EventController extends Controller
{
    /**
     * Dashboard Utama
     */
    public function index()
    {
        $totalEvents = Event::count();
        $totalEmployees = TonasaEmployee::count();

        return view('admin.dashboard', compact('totalEvents', 'totalEmployees'));
    }

    /**
     * Halaman Manajemen Agenda
     */
    public function agenda()
    {
        // Ambil event hari ini berdasarkan kolom 'date'
        $todayEvents = Event::whereDate('date', Carbon::today())
                        ->latest()
                        ->get();

        return view('admin.agenda.index', compact('todayEvents'));
    }

    /**
     * Form Buat Agenda
     */
    public function create()
    {
        return view('dashboard.create');
    }

    /**
     * Simpan Agenda (FIX: User ID & Date)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',             // Wajib ada karena kolom 'date' terpisah
            'start_time' => 'required',            // Format H:i dari input time
            'end_time' => 'required',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // 2. Tambahkan user_id manual (Solusi Error 1364)
        $validated['user_id'] = Auth::id();

        // 3. Simpan ke Database
        Event::create($validated);

        return redirect()->route('event.agenda')
            ->with('success', 'Agenda berhasil dibuat dan siap digunakan.');
    }

    /**
     * Halaman Monitor (Show)
     */
 public function show(Event $event)
{
    // Ini tetap untuk menampilkan view detail/modal
    return view('admin.show', compact('event'));
}

public function monitor(Event $event)
{
    // Method baru khusus untuk halaman monitoring
    // Pastikan relasi attendances dimuat (eager loading)
    $event->load('attendances'); 
    return view('admin.monitor', compact('event'));
}

    /**
     * Form Edit
     */
    public function edit(Event $event)
    {
        return view('dashboard.edit', compact('event'));
    }

    /**
     * Update Agenda
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required|string|max:255',
        ]);

        // Update data (user_id tidak perlu di-update)
        $event->update($validated);

        return redirect()->route('event.agenda')
            ->with('success', 'Agenda berhasil diperbarui.');
    }

    /**
     * Hapus Agenda
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('event.agenda')
            ->with('success', 'Agenda berhasil dihapus.');
    }

    public function reports()
    {
        $events = Event::withCount('attendances')->latest()->get();
        return view('dashboard.reports', compact('events'));
    }

    public function showQrCode(Event $event)
    {
        $url = route('attendance.form', $event->id);
        $qrcode = QrCode::size(300)->generate($url);
        return view('dashboard.qrcode', compact('event', 'qrcode', 'url'));
    }
    
    public function downloadQrCode(Event $event)
    {
        $url = route('attendance.form', $event->id);
        return response()->streamDownload(
            function () use ($url) {
                echo QrCode::format('png')->size(300)->generate($url);
            },
            'qrcode-' . $event->id . '.png',
            ['Content-Type' => 'image/png']
        );
    }
}