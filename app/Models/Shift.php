<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'position_index',

        // If your table stores who this shift is assigned to:
        // adjust the column name if yours is `staff_id` instead.
        'staff_profile_id',
    ];

    protected $casts = [
        'start_at'      => 'datetime',
        'end_at'        => 'datetime',
        'skills_json'   => 'array',
        'position_index' => 'integer',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(RotaPeriod::class, 'rota_period_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(ShiftAssignment::class);
    }

    /**
     * The staff profile assigned to this shift (one main carer per shift).
     * If your column is `staff_id` instead of `staff_profile_id`,
     * change the 2nd argument to 'staff_id'.
     */
    public function staffProfile(): BelongsTo
    {
        return $this->belongsTo(StaffProfile::class, 'staff_profile_id');
    }

    public function getDurationMinutesAttribute(): int
    {
        $mins = $this->start_at && $this->end_at
            ? $this->start_at->diffInMinutes($this->end_at)
            : 0;

        return max(0, $mins - (int) $this->break_minutes);
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
