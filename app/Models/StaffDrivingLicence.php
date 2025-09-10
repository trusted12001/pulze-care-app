<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffDrivingLicence extends Model
{
    protected $fillable = [
        'tenant_id','staff_profile_id','licence_number','categories','expires_at',
        'business_insurance_confirmed','document_file_id',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'business_insurance_confirmed' => 'boolean',
    ];

    public function staffProfile() { return $this->belongsTo(\App\Models\StaffProfile::class); }
}
