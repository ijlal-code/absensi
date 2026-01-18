<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TonasaEmployee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventController extends Controller
{
    /**
     * Dashboard Utama (Menu Navigasi).
     * Hanya menampilkan kartu menu pilihan (Manajemen Agenda & Data Karyawan).
     */
    public function index()
    {
        // Data ringkasan untuk tampilan kartu menu
        $totalEvents = Event::count();
        $totalEmployees = TonasaEmployee::count();

        return view('admin.dashboard', compact('totalEvents', 'totalEmployees'));
    }

    /**
     * Halaman Manajemen Agenda.
     * Menampilkan tombol buat agenda baru & daftar agenda hari ini.
     */
    public function agenda()
    {
        // Ambil event khusus HARI INI untuk monitoring
        $todayEvents = Event::whereDate('start_time', Carbon::today())
                        ->orderBy('start_time', 'asc')
                        ->get();

        return view('admin.agenda.index', compact('todayEvents'));
    }

    /**
     * Form Buat Agenda Baru.
     */
    public function create()
    {
        return view('dashboard.create');
    }

    /**
     * Simpan Agenda Baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Event::create($validated);

        // Redirect ke halaman Manajemen Agenda (bukan dashboard utama)
        return redirect()->route('event.agenda')
            ->with('success', 'Agenda berhasil dibuat dan siap digunakan.');
    }

    /**
     * Tampilkan Detail Agenda (Monitor Absensi).
     */
    public function show($id)
    {
        $event = Event::with('attendances')->findOrFail($id);
        return view('admin.show', compact('event'));
    }

    /**
     * Form Edit Agenda.
     */
    public function edit(Event $event)
    {
        return view('dashboard.edit', compact('event'));
    }

    /**
     * Update Data Agenda.
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required',
        ]);

        $event->update($request->all());

        return redirect()->route('event.agenda')
            ->with('success', 'Agenda berhasil diperbarui.');
    }

    /**
     * Hapus Agenda.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('event.agenda')
            ->with('success', 'Agenda berhasil dihapus.');
    }

    /**
     * Halaman Laporan (Rekapitulasi).
     */
    public function reports()
    {
        $events = Event::withCount('attendances')->latest()->get();
        return view('dashboard.reports', compact('events'));
    }

    /**
     * Tampilkan Halaman QR Code.
     */
    public function showQrCode(Event $event)
    {
        $url = route('attendance.form', $event->id);
        
        // Generate QR Code sederhana untuk view
        $qrcode = QrCode::size(300)->generate($url);
        
        return view('dashboard.qrcode', compact('event', 'qrcode', 'url'));
    }
    
    /**
     * Download QR Code (PNG).
     */
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
    
    /**
     * Dashboard Khusus Admin (Jika dipisah logic-nya).
     * Saat ini diarahkan ke logic index yang sama.
     */
    public function adminDashboard()
    {
        return $this->index();
    }
}