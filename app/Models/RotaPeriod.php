<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RotaPeriod extends Model
{
    protected $fillable = [
        'location_id',
        'start_date',
        'end_date',
        'status',
        'generated_by',
        'published_at',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'published_at' => 'datetime',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // ✅ Only ONE relation method, lowercase
    public function shifts()
    {
        return $this->hasMany(Shift::class, 'rota_period_id');
    }

    public function scopeForDate($q, $date)
    {
        return $q->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date);
    }
}
