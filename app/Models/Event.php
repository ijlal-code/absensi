<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    protected $guarded = [];

    // Helper untuk status absensi (boolean)
    public function getIsOpenAttribute()
    {
        $now = Carbon::now(); // Waktu sekarang 
        // Gabungkan tanggal & jam acara
        $start = Carbon::parse($this->date . ' ' . $this->start_time);
        $end = Carbon::parse($this->date . ' ' . $this->end_time);

        // Cek apakah sekarang berada di antara waktu mulai dan selesai
        return $now->between($start, $end);
    }

    // Helper untuk status spesifik (string)
    public function getStatusAttribute()
    {
        $now = Carbon::now();
        $start = Carbon::parse($this->date . ' ' . $this->start_time);
        $end = Carbon::parse($this->date . ' ' . $this->end_time);

        if ($now->lt($start)) {
            return 'pending'; // Belum mulai
        } elseif ($now->gt($end)) {
            return 'closed'; // Sudah lewat
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