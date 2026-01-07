<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function showForm(Event $event)
    {
        return view('attendance.form', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        // --- LOGIKA WAKTU (FIXED ASIA/MAKASSAR - WITA) ---
        // Kita gunakan logika is_open dari Model Event yang sudah diperbaiki ke WITA
        
        if (!$event->is_open) {
            // Debugging: Tampilkan waktu server (WITA) agar user paham
            $serverTime = Carbon::now('Asia/Makassar')->format('H:i');
            
            if ($event->status === 'pending') {
                return back()->with('error', "Absensi belum dibuka. Sekarang pukul $serverTime WITA. Jadwal Mulai: $event->start_time");
            }
            if ($event->status === 'closed') {
                return back()->with('error', "Absensi sudah ditutup. Sekarang pukul $serverTime WITA. Jadwal Selesai: $event->end_time");
            }
        }

        // --- VALIDASI INPUT ---
        $request->validate([
            'name' => 'required|string|max:255',
            'work_unit' => 'required|string|max:255',
            'signature_type' => 'required|in:draw,upload',
            'signature_draw' => 'required_if:signature_type,draw', 
            'signature_upload' => 'required_if:signature_type,upload|image|max:5120',
        ], [
            'signature_draw.required_if' => 'Tanda tangan wajib diisi (gambar/upload).',
        ]);

        try {
            $signaturePath = null;

            // --- PROSES SIMPAN TANDA TANGAN ---
            if ($request->signature_type === 'draw') {
                $image_parts = explode(";base64,", $request->signature_draw);
                
                if (count($image_parts) < 2) {
                     return back()->with('error', 'Gagal memproses tanda tangan.');
                }
                
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'signatures/' . uniqid() . '_' . time() . '.png';
                
                Storage::disk('public')->put($fileName, $image_base64);
                $signaturePath = $fileName;

            } else {
                if ($request->hasFile('signature_upload')) {
                    $signaturePath = $request->file('signature_upload')->store('signatures', 'public');
                }
            }

            // --- SIMPAN KE DATABASE ---
            Attendance::create([
                'event_id' => $event->id,
                'name' => $request->name,
                'work_unit' => $request->work_unit,
                'signature_path' => $signaturePath,
            ]);

            return back()->with('success', 'Terima kasih, absensi Anda berhasil disimpan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function downloadPdf(Event $event)
    {
        $attendances = $event->attendances;
        $pdf = Pdf::loadView('attendance.pdf', compact('event', 'attendances'));
        return $pdf->download('absensi-'.$event->title.'.pdf');
    }
}