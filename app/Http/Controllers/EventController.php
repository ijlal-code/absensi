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
    /**
     * Dashboard Utama (Menangani Admin & User)
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

        // JIKA USER BIASA: Tampilkan Pilihan Card (Navigasi)
        else {
            return view('dashboard.user_index');
        }
    }

    /**
     * Menampilkan Tabel Agenda Khusus Buatan Admin (Untuk User)
     */
    public function listAdminAgendas()
    {
        // Ambil event yang dibuat oleh user dengan role 'admin'
        $adminEvents = Event::whereHas('user', function($query) {
            $query->where('role', 'admin');
        })->latest()->get();

        return view('dashboard.admin_agendas_list', compact('adminEvents'));
    }

    /**
     * Halaman Manajemen Agenda
     */
  public function agenda()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // 1. Ambil semua event hari ini & load data user
        $query = \App\Models\Event::whereDate('date', \Carbon\Carbon::today())
                    ->with('user'); 

        // 2. Filter untuk User Biasa (Hanya lihat punya sendiri)
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        // 3. Eksekusi Query
        $eventsCollection = $query->get();

        // 4. Cari ID Agenda Admin yang PALING BARU DIBUAT (Created At terakhir)
        // Ini akan kita gunakan untuk menaruhnya di posisi paling atas (Ranking 0)
        $latestAdminEvent = \App\Models\Event::whereDate('date', \Carbon\Carbon::today())
            ->whereHas('user', function($q) {
                $q->where('role', 'admin');
            })
            ->latest('created_at') // Urutkan berdasarkan waktu pembuatan
            ->first();

        $latestAdminEventId = $latestAdminEvent ? $latestAdminEvent->id : null;

        // 5. LOGIKA SORTING CANGGIH (Multi-Level Sorting)
        if ($user->isAdmin()) {
            $todayEvents = $eventsCollection->sortBy([
                function ($event) use ($latestAdminEventId) {
                    // LEVEL 1: Jika ini adalah agenda Admin TERBARU -> Rank 0 (Paling Atas)
                    if ($event->id === $latestAdminEventId) {
                        return 0;
                    }
                    
                    // LEVEL 2: Jika ini agenda Admin SISANYA -> Rank 1 (Di bawah yg terbaru)
                    if ($event->user->role === 'admin') {
                        return 1;
                    }

                    // LEVEL 3: Jika ini agenda User -> Rank 2 (Paling Bawah)
                    return 2;
                },
                // LEVEL 4: Jika rankingnya sama (misal sama-sama rank 1), urutkan berdasarkan Jam Mulai
                ['start_time', 'asc'],
            ]);
        } else {
            // Untuk User biasa, cukup urutkan berdasarkan jam
            $todayEvents = $eventsCollection->sortBy('start_time');
        }

        return view('admin.agenda.index', compact('todayEvents', 'latestAdminEventId'));
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
    // UPDATE METHOD STORE AGAR AMAN
    public function store(Request $request)
    {
        // Cek izin dulu
        if (!Auth::user()->isAdmin() && !Auth::user()->hasPermission('create_events')) {
            abort(403, 'Anda tidak memiliki akses untuk membuat agenda.');
        }

        // ... validasi dan save code (copy dari file sebelumnya) ...
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $validated['user_id'] = Auth::id();
        Event::create($validated);

        return redirect()->route('event.agenda')
            ->with('success', 'Agenda berhasil dibuat.');
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