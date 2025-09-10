<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffAvailabilityPreference extends Model
{
    protected $fillable = [
        'tenant_id','staff_profile_id','day_of_week','available_from','available_to','preference',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
    ];

    public function staffProfile() { return $this->belongsTo(\App\Models\StaffProfile::class); }
}
