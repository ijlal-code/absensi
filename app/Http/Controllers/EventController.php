<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TonasaEmployee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth; // PENTING: Untuk ambil Auth::id()
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Dashboard Utama
     */
    public function index()
    {
        $user = Auth::user();

        // JIKA ADMIN: Tampilkan Statistik
        if ($user->isAdmin()) {
            $totalEvents = Event::count();
            $totalEmployees = TonasaEmployee::count();
            return view('admin.dashboard', compact('totalEvents', 'totalEmployees'));
        }

        // JIKA USER: Tampilkan Agenda buatan Admin
        else {
            // Ambil agenda yang dibuat oleh user dengan role admin
            $adminEvents = Event::whereHas('user', function($query) {
                $query->where('role', 'admin');
            })->latest()->get();

            // Kita gunakan view yang berbeda atau view dashboard dimodifikasi
            // Disini saya arahkan ke view baru khusus user dashboard agar rapi
            return view('dashboard.user_index', compact('adminEvents'));
        }
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
     * Hapus Agenda & File Tanda Tangan
     */
    public function destroy(Event $event)
    {
        // 1. Hapus file tanda tangan fisik di storage
        // Load relasi attendances untuk mendapatkan path tanda tangan
        $event->load('attendances');

        foreach ($event->attendances as $attendance) {
            if ($attendance->signature_path && Storage::disk('public')->exists($attendance->signature_path)) {
                Storage::disk('public')->delete($attendance->signature_path);
            }
        }

        // 2. Hapus data event di database
        $event->delete();

        // Gunakan back() agar kembali ke halaman pemanggil (bisa dari Agenda atau Reports)
        return back()->with('success', 'Agenda dan seluruh data tanda tangan berhasil dihapus.');
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