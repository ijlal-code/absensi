<?php
namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index() {
        // Tampilkan event hari ini
        $todayEvents = Event::whereDate('date', Carbon::today())->get();
        return view('dashboard.index', compact('todayEvents'));
    }

    public function create() { return view('dashboard.create'); }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required', 'date' => 'required|date',
            'start_time' => 'required', 'end_time' => 'required|after:start_time',
            'location' => 'required',
        ]);
        Event::create($request->all());
        return redirect()->route('dashboard')->with('success', 'Acara berhasil dibuat!');
    }

    // NEW: Edit Acara
    public function edit(Event $event) { return view('dashboard.edit', compact('event')); }

    public function update(Request $request, Event $event) {
        $request->validate([
            'title' => 'required', 'date' => 'required',
            'start_time' => 'required', 'end_time' => 'required', 'location' => 'required'
        ]);
        $event->update($request->all());
        return redirect()->route('dashboard')->with('success', 'Acara berhasil diperbarui!');
    }

    // NEW: Hapus Acara
    public function destroy(Event $event) {
        $event->delete();
        return back()->with('success', 'Acara berhasil dihapus!');
    }

    public function show(Event $event) {
        $attendances = $event->attendances()->latest()->get();
        return view('dashboard.show', compact('event', 'attendances'));
    }

    public function reports() {
        $events = Event::latest('date')->get();
        return view('dashboard.reports', compact('events'));
    }
}