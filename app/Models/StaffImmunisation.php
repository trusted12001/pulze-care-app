<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffImmunisation extends Model
{
    protected $fillable = [
        'tenant_id','staff_profile_id','vaccine','dose','administered_at','evidence_file_id','notes',
    ];

    protected $casts = [
        'administered_at' => 'date',
    ];

    public function staffProfile() { return $this->belongsTo(\App\Models\StaffProfile::class); }
}
