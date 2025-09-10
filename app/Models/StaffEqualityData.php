<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffEqualityData extends Model
{
    protected $table = 'staff_equality_data';

    protected $fillable = [
        'tenant_id','staff_profile_id','ethnicity','religion','disability',
        'gender_identity','sexual_orientation','data_source',
    ];

    protected $casts = [
        'disability' => 'boolean',
    ];

    public function staffProfile() { return $this->belongsTo(\App\Models\StaffProfile::class); }
}
