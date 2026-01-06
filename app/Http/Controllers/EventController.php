<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // Dashboard User (Hanya lihat acara miliknya sendiri)
    public function index() {
        if (Auth::user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
        $user = Auth::user();
        
        $todayEvents = Event::where('user_id', $user->id) 
                            ->whereDate('date', Carbon::today())
                            ->latest()
                            ->get();
                            
        return view('dashboard.index', compact('todayEvents'));
    }

    // Dashboard Khusus Admin (Bisa lihat semua)
    public function adminDashboard()
    {
        // Admin melihat semua event hari ini
        $todayEvents = Event::whereDate('date', Carbon::today())->latest()->get();
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

        // Gabungkan data input dengan ID user yang sedang login
        $data = $request->all();
        $data['user_id'] = Auth::id(); 

        Event::create($data);

        // Redirect bedakan antara admin dan user biasa
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Acara berhasil dibuat!');
        }
        return redirect()->route('dashboard')->with('success', 'Acara berhasil dibuat!');
    }

    // Edit Acara
    public function edit(Event $event) { 
        // Proteksi: User tak boleh edit punya orang lain
        if (Auth::user()->role !== 'admin' && $event->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak');
        }
        return view('dashboard.edit', compact('event')); 
    }

    public function update(Request $request, Event $event) {
        if (Auth::user()->role !== 'admin' && $event->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak');
        }

        $request->validate([
            'title' => 'required', 
            'date' => 'required',
            'start_time' => 'required', 
            'end_time' => 'required', 
            'location' => 'required'
        ]);
        
        // Update data event. user_id tidak perlu di-update.
        $event->update($request->except(['user_id']));
        
        return back()->with('success', 'Acara berhasil diperbarui!');
    }

    // Hapus Acara
    public function destroy(Event $event) {
        if (Auth::user()->role !== 'admin' && $event->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak');
        }

        $event->delete();
        return back()->with('success', 'Acara berhasil dihapus!');
    }

    // Monitor
    public function show(Event $event) {
        if (Auth::user()->role !== 'admin' && $event->user_id !== Auth::id()) {
            abort(403);
        }

        $attendances = $event->attendances()->latest()->get();
        return view('admin.show', compact('event', 'attendances'));
    }

    // Laporan
    public function reports() {
        if (Auth::user()->role === 'admin') {
            $events = Event::withCount('attendances')->latest('date')->get();
        } else {
            $events = Event::where('user_id', Auth::id())
                           ->withCount('attendances')
                           ->latest('date')
                           ->get();
        }
        return view('dashboard.reports', compact('events'));
    }
}