<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    protected $guarded = ['id'];

    // Relasi ke Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // --- LOGIKA STATUS & WAKTU (WITA / Asia/Makassar) ---

    // Atribut Virtual: Cek apakah event sedang buka
    public function getIsOpenAttribute()
    {
        // Gunakan Waktu Makassar (WITA)
        $now = Carbon::now('Asia/Makassar')->format('H:i:s');
        $today = Carbon::now('Asia/Makassar')->format('Y-m-d');

        // Cek Tanggal
        if ($this->date !== $today) {
            return false;
        }

        // Cek Jam (Realtime)
        return $now >= $this->start_time && $now <= $this->end_time;
    }

    // Atribut Virtual: Status Text (Pending/Open/Closed)
    public function getStatusAttribute()
    {
        // Gunakan Waktu Makassar (WITA)
        $now = Carbon::now('Asia/Makassar')->format('H:i:s');
        $today = Carbon::now('Asia/Makassar')->format('Y-m-d');

        // Logika Tanggal
        if ($this->date > $today) {
            return 'pending'; // Hari belum tiba
        } elseif ($this->date < $today) {
            return 'closed'; // Hari sudah lewat
        }

        // Logika Jam (Jika hari ini)
        if ($now < $this->start_time) {
            return 'pending'; // Belum mulai jamnya
        } elseif ($now > $this->end_time) {
            return 'closed'; // Sudah lewat jamnya
        }

        return 'open'; // Sedang berlangsung
    }
    
    public function getLinkAttribute()
    {
        return route('attendance.form', $this->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}