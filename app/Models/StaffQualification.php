<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffQualification extends Model
{
    protected $fillable = [
        'tenant_id','staff_profile_id','level','title','institution','awarded_at','certificate_file_id',
    ];

    protected $casts = [
        'awarded_at' => 'date',
    ];

    public function staffProfile() { return $this->belongsTo(\App\Models\StaffProfile::class); }
}
