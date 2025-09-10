<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffAdjustment extends Model
{
    protected $fillable = [
        'tenant_id','staff_profile_id','type','in_place_from','notes',
    ];

    protected $casts = [
        'in_place_from' => 'date',
    ];

    public function staffProfile() { return $this->belongsTo(\App\Models\StaffProfile::class); }
}
