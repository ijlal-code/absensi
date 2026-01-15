<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // Dashboard User / Penyelenggara
    public function index() {
        // Jika Admin nyasar ke sini, lempar ke dashboard admin
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $user = Auth::user();

        // PERBAIKAN: Gunakan Timezone Spesifik (WITA/Makassar) untuk menentukan 'Hari Ini'
        // Jika menggunakan default Carbon::today(), server UTC seringkali masih 'kemarin' saat pagi hari di Indonesia.
        // Anda bisa mengganti 'Asia/Makassar' dengan 'Asia/Jakarta' jika perlu WIB.
        $todayDate = Carbon::now('Asia/Makassar')->toDateString();

        // Menampilkan event hari ini sesuai tanggal di Timezone yang benar
        $todayEvents = Event::where('user_id', $user->id) 
                            ->whereDate('date', $todayDate)
                            ->latest()
                            ->get();
                            
        // Pastikan file view ada di resources/views/dashboard/index.blade.php
        return view('dashboard.index', compact('todayEvents'));
    }

    // Dashboard Khusus Admin
    public function adminDashboard()
    {
        // Perbaikan juga diterapkan untuk Admin agar konsisten
        $todayDate = Carbon::now('Asia/Makassar')->toDateString();
        
        $todayEvents = Event::whereDate('date', $todayDate)->latest()->get();
        $totalEvents = Event::count();
        return view('admin.dashboard', compact('todayEvents', 'totalEvents'));
    }

    public function create() { 
        return view('dashboard.create'); 
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required', 
            'date' => 'required|date',
            'start_time' => 'required', 
            'end_time' => 'required|after:start_time',
            'location' => 'required',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id(); 

        Event::create($data);

        return redirect()->route('dashboard')->with('success', 'Agenda berhasil dibuat!');
    }

    // Edit Agenda
    public function edit(Event $event) { 
        if (!Auth::user()->isAdmin() && $event->user_id !== Auth::id()) {
            abort(403);
        }
        return view('dashboard.edit', compact('event')); 
    }

    // Update Agenda
    public function update(Request $request, Event $event) {
        if (!Auth::user()->isAdmin() && $event->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required', 
            'date' => 'required',
            'start_time' => 'required', 
            'end_time' => 'required', 
            'location' => 'required'
        ]);
        
        $event->update($request->except(['user_id']));
        
        return redirect()->route('dashboard')->with('success', 'Agenda berhasil diperbarui!');
    }

    // Hapus Agenda
    public function destroy(Event $event) {
        if (!Auth::user()->isAdmin() && $event->user_id !== Auth::id()) {
            abort(403);
        }

        $event->delete();
        return back()->with('success', 'Agenda berhasil dihapus!');
    }

    // Monitor Peserta (Admin/Penyelenggara)
    public function show(Event $event) {
        // Cek Hak Akses
        if (!Auth::user()->isAdmin() && $event->user_id !== Auth::id()) {
            abort(403);
        }

        // Gunakan oldest() agar urutan 1 adalah yang absen duluan
        $attendances = $event->attendances()->oldest()->get();
        
        return view('admin.show', compact('event', 'attendances'));
    }

    // Laporan
    public function reports() {
        if (Auth::user()->isAdmin()) {
            $events = Event::withCount('attendances')->latest('date')->get();
        } else {
            $events = Event::where('user_id', Auth::id())
                           ->withCount('attendances')
                           ->latest('date')
                           ->get();
        }
        return view('dashboard.reports', compact('events'));
    }

    // Menampilkan Halaman QR Code
    public function showQrCode(Event $event) {
        // Cek Hak Akses (Admin atau Pemilik Event)
        if (!Auth::user()->isAdmin() && $event->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Generate Link Absensi
        $url = route('attendance.form', $event->id);
        
        // Tampilkan view khusus QR
        return view('dashboard.qrcode', compact('event', 'url'));
    }

    // Mengunduh QR Code (PNG)
    public function downloadQrCode(Event $event) {
        // 1. Cek Hak Akses
        if (!Auth::user()->isAdmin() && $event->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. Tentukan URL Absensi
        $url = route('attendance.form', $event->id);

        // 3. Generate QR Code ke dalam variabel
        $qrCode = QrCode::format('png')
                        ->size(500)
                        ->margin(2)
                        ->generate($url);

        // 4. Return response dengan header yang sesuai
        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qrcode-' . \Illuminate\Support\Str::slug($event->title) . '.png"');
    }
}