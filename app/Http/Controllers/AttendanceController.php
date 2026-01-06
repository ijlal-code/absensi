<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceController extends Controller
{
    public function showForm(Event $event)
    {
        if (!$event->is_open) {
            return view('attendance.closed', compact('event'));
        }
        return view('attendance.form', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        if (!$event->is_open) {
            return back()->with('error', 'Maaf, waktu absensi sudah habis!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'work_unit' => 'required|string|max:255',
            'signature_type' => 'required|in:draw,upload',
            'signature_draw' => 'required_if:signature_type,draw',
            'signature_upload' => 'required_if:signature_type,upload|image|max:2048',
        ]);

        $signaturePath = null;

        if ($request->signature_type === 'draw') {
            $image_parts = explode(";base64,", $request->signature_draw);
            // Cek apakah data base64 valid
            if (count($image_parts) < 2) {
                 return back()->with('error', 'Tanda tangan tidak valid. Silakan ulangi.');
            }
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'signatures/' . uniqid() . '.png';
            Storage::disk('public')->put($fileName, $image_base64);
            $signaturePath = $fileName;

        } else {
            $path = $request->file('signature_upload')->store('signatures', 'public');
            $signaturePath = $path;
        }

        Attendance::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'work_unit' => $request->work_unit,
            'signature_path' => $signaturePath,
        ]);

        return back()->with('success', 'Absensi berhasil dicatat!');
    }

    public function downloadPdf(Event $event)
    {
        $attendances = $event->attendances;
        $pdf = Pdf::loadView('attendance.pdf', compact('event', 'attendances'));
        return $pdf->download('absensi-'.$event->id.'.pdf');
    }
}