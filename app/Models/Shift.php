<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'rota_period_id',
        'location_id',
        'role',
        'start_at',
        'end_at',
        'break_minutes',
        'skills_json',
        'status',
        'notes',
        'position_index'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'skills_json' => 'array',
        'position_index' => 'integer',
    ];

    public function period()
    {
        return $this->belongsTo(RotaPeriod::class, 'rota_period_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function assignments()
    {
        return $this->hasMany(ShiftAssignment::class);
    }

    public function getDurationMinutesAttribute(): int
    {
        $mins = $this->start_at && $this->end_at ? $this->start_at->diffInMinutes($this->end_at) : 0;
        return max(0, $mins - (int)$this->break_minutes);
    }

    public function timesheets(): HasMany
    {
        return $this->hasMany(Timesheet::class);
    }

    /**
     * Convenience: the main/primary timesheet record for this shift
     * (e.g. the one created when the staff checks in).
     */
    public function latestTimesheet(): HasOne
    {
        return $this->hasOne(Timesheet::class)->latestOfMany();
    }
}
