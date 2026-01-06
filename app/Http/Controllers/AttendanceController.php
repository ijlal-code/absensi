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
    // Menampilkan Form
   public function showForm(Event $event)
    {
        // Cek Batas Waktu
        if (!$event->is_open) {
            return view('attendance.closed', compact('event')); // Tampilkan halaman tutup
        }
        return view('attendance.form', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        // Cek lagi saat submit
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

        // Logika Penyimpanan Tanda Tangan
        if ($request->signature_type === 'draw') {
            // 1. Ambil data base64
            $image_parts = explode(";base64,", $request->signature_draw);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_base64 = base64_decode($image_parts[1]);
            
            // 2. Buat nama file unik
            $fileName = 'signatures/' . uniqid() . '.png';
            
            // 3. Simpan ke storage public
            Storage::disk('public')->put($fileName, $image_base64);
            $signaturePath = $fileName;

        } else {
            // Jika Upload Foto
            $path = $request->file('signature_upload')->store('signatures', 'public');
            $signaturePath = $path;
        }

        // Simpan ke Database
        Attendance::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'work_unit' => $request->work_unit,
            'signature_path' => $signaturePath,
        ]);

        return back()->with('success', 'Absensi berhasil dicatat!');
    }

    // Download PDF
    public function downloadPdf(Event $event)
    {
        $attendances = $event->attendances;
        
        $pdf = Pdf::loadView('attendance.pdf', compact('event', 'attendances'));
        return $pdf->download('absensi-'.$event->id.'.pdf');
    }
}