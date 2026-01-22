<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\ActionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF; // Pastikan package dompdf terinstall (composer require barryvdh/laravel-dompdf)

class MeetingController extends Controller
{
    public function index()
    {
        // Ambil rapat, urutkan dari yang terbaru
        $meetings = Meeting::with('actionItems')->latest('date')->get();
        return view('meetings.index', compact('meetings'));
    }

    public function create()
    {
        return view('meetings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_of_meeting' => 'required',
            'facilitator' => 'required',
            'date' => 'required|date',
            'start_time' => 'required',
            'location' => 'required',
            'notulis' => 'required', // Validasi baru
            'action_items' => 'array',
        ]);

        $meeting = Meeting::create([
            'user_id' => Auth::id(),
            'type_of_meeting' => $request->type_of_meeting,
            'facilitator' => $request->facilitator,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            // Tambahkan field baru ini:
            'notulis' => $request->notulis,
            'presenter' => $request->presenter, // Boleh null
            'meeting_duration' => $request->meeting_duration, // Boleh null
            'attendees_list' => $request->attendees_list ?? 'Terlampir', // Default Terlampir jika kosong
        ]);

        // Simpan Action Items
        if ($request->has('action_items')) {
            foreach ($request->action_items as $item) {
                // Pastikan item tidak kosong
                if(!empty($item['task'])) {
                    $meeting->actionItems()->create([
                        'action_item' => $item['task'],
                        'pic' => $item['pic'] ?? '-',
                        'deadline' => $item['deadline'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('meetings.index')->with('success', 'Agenda Rapat berhasil dibuat.');
    }

    public function edit(Meeting $meeting)
    {
        // Pastikan relasi actionItems dimuat
        $meeting->load('actionItems');
        return view('meetings.edit', compact('meeting'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $request->validate([
            'type_of_meeting' => 'required',
            'facilitator' => 'required',
            'date' => 'required|date',
            'start_time' => 'required',
            'location' => 'required',
            'notulis' => 'required',
            // Validasi lain sesuai kebutuhan
        ]);

        // 1. Update Data Header Rapat
        $meeting->update([
            'type_of_meeting' => $request->type_of_meeting,
            'facilitator' => $request->facilitator,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'notulis' => $request->notulis,
            'presenter' => $request->presenter,
            'meeting_duration' => $request->meeting_duration,
            'attendees_list' => $request->attendees_list,
        ]);
        
        // 2. Update Action Items (Hapus Lama -> Buat Baru)
        // Ini cara paling aman untuk menangani perubahan urutan atau penghapusan item
        $meeting->actionItems()->delete();

        if ($request->has('action_items')) {
            foreach ($request->action_items as $item) {
                if(!empty($item['task'])) {
                    $meeting->actionItems()->create([
                        'action_item' => $item['task'],
                        'pic' => $item['pic'] ?? '-',
                        'deadline' => $item['deadline'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('meetings.index')->with('success', 'Agenda Rapat diperbarui.');
    }

    public function destroy(Meeting $meeting)
    {
        $meeting->delete();
        return back()->with('success', 'Data Rapat dihapus.');
    }

    public function reports()
    {
        $meetings = Meeting::with('actionItems')->latest()->get();
        return view('meetings.reports', compact('meetings'));
    }

    public function downloadPdf(Meeting $meeting)
    {
        $meeting->load('actionItems');
        
        // Gunakan view khusus PDF
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('meetings.pdf', compact('meeting'));
        
        return $pdf->download('MOM-' . $meeting->date . '.pdf');
    }
}