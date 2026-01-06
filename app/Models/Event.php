<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    protected $guarded = [];

    // Helper untuk status absensi
    public function getIsOpenAttribute()
    {
        $now = Carbon::now();
        $start = Carbon::parse($this->date . ' ' . $this->start_time);
        $end = Carbon::parse($this->date . ' ' . $this->end_time);

        return $now->between($start, $end);
    }
    
    // Helper format link
    public function getLinkAttribute()
    {
        return route('attendance.form', $this->id);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}