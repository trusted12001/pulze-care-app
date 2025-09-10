<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffEmergencyContact extends Model
{
    protected $fillable = [
        'tenant_id','staff_profile_id','name','relationship','phone','email','address',
    ];

    public function staffProfile() { return $this->belongsTo(\App\Models\StaffProfile::class); }
}
