<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actionItems()
    {
        return $this->hasMany(ActionItem::class);
    }
    
    // Helper untuk cek apakah ada deadline yang terlewati/hari ini
    public function hasDueDeadlines()
    {
        return $this->actionItems()->where('is_completed', false)
            ->whereDate('deadline', '<=', now())
            ->exists();
    }
}