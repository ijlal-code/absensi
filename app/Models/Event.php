<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    protected $guarded = [];

    // Helper: Cek apakah absensi sedang dibuka (Boolean)
    public function getIsOpenAttribute()
    {
        // PENTING: Paksa gunakan waktu Jakarta
        $now = Carbon::now('Asia/Jakarta'); 
        
        // Parse waktu acara sebagai waktu Jakarta
        $start = Carbon::parse($this->date . ' ' . $this->start_time, 'Asia/Jakarta');
        $end = Carbon::parse($this->date . ' ' . $this->end_time, 'Asia/Jakarta');

        // Cek apakah 'sekarang' ada di antara awal dan akhir
        return $now->between($start, $end);
    }

    // Helper: Cek status text (pending/closed/open)
    public function getStatusAttribute()
    {
        $now = Carbon::now('Asia/Jakarta');
        $start = Carbon::parse($this->date . ' ' . $this->start_time, 'Asia/Jakarta');
        $end = Carbon::parse($this->date . ' ' . $this->end_time, 'Asia/Jakarta');

        if ($now->lt($start)) {
            return 'pending'; // Belum mulai
        } elseif ($now->gt($end)) {
            return 'closed'; // Sudah berakhir
        } else {
            return 'open'; // Sedang berlangsung
        }
    }
    
    public function getLinkAttribute()
    {
        return route('attendance.form', $this->id);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}