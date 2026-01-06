<?php

namespace App\Http\Controllers;

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
        // Menampilkan event hari ini sesuai Timezone
        $todayEvents = Event::where('user_id', $user->id) 
                            ->whereDate('date', Carbon::today())
                            ->latest()
                            ->get();
                            
        // Pastikan file view ada di resources/views/dashboard/index.blade.php
        return view('dashboard.index', compact('todayEvents'));
    }

    // Dashboard Khusus Admin
    public function adminDashboard()
    {
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

        $data = $request->all();
        $data['user_id'] = Auth::id(); 

        Event::create($data);

        return redirect()->route('dashboard')->with('success', 'Acara berhasil dibuat!');
    }

    // Edit Acara
    public function edit(Event $event) { 
        if (!Auth::user()->isAdmin() && $event->user_id !== Auth::id()) {
            abort(403);
        }
        return view('dashboard.edit', compact('event')); 
    }

    // Update Acara
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
        
        return redirect()->route('dashboard')->with('success', 'Acara berhasil diperbarui!');
    }

    // Hapus Acara
    public function destroy(Event $event) {
        if (!Auth::user()->isAdmin() && $event->user_id !== Auth::id()) {
            abort(403);
        }

        $event->delete();
        return back()->with('success', 'Acara berhasil dihapus!');
    }

    // Monitor Peserta
    public function show(Event $event) {
        if (!Auth::user()->isAdmin() && $event->user_id !== Auth::id()) {
            abort(403);
        }
        $attendances = $event->attendances()->latest()->get();
        // Pastikan punya view admin/show.blade.php atau sesuaikan
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
}