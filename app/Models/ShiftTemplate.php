<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftTemplate extends Model
{
    protected $fillable = [
        'location_id','name','role','start_time','end_time',
        'break_minutes','headcount','skills_json','days_of_week_json',
        'paid_flag','active','notes',
    ];

    protected $casts = [
        'skills_json'      => 'array',
        'days_of_week_json'=> 'array',
        'paid_flag'        => 'boolean',
        'active'           => 'boolean',
    ];

    public function location() { return $this->belongsTo(Location::class); }

    // Helper: returns ['mon','tue',...] or null = all days
    public function days(): ?array
    {
        return $this->days_of_week_json ?: null;
    }
}
