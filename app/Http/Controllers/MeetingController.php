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
            'action_items' => 'array', // Array dari input dinamis
        ]);

        $meeting = Meeting::create([
            'user_id' => Auth::id(),
            'type_of_meeting' => $request->type_of_meeting,
            'facilitator' => $request->facilitator,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
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
        $meeting->load('actionItems');
        return view('meetings.edit', compact('meeting'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        // Logic update mirip store, hapus action items lama, buat baru (cara simpel)
        // Atau update existing. Untuk ringkasnya, kita update header saja disini
        // Implementasi detail disesuaikan kebutuhan.
        
        $meeting->update($request->except('action_items'));
        
        // Handle update action items (bisa menggunakan logika sync atau delete-insert)
        $meeting->actionItems()->delete();
        if ($request->has('action_items')) {
            foreach ($request->action_items as $item) {
                if(!empty($item['task'])) {
                    $meeting->actionItems()->create([
                        'action_item' => $item['task'],
                        'pic' => $item['pic'],
                        'deadline' => $item['deadline'],
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