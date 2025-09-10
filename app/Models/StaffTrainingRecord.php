<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffTrainingRecord extends Model
{
    protected $fillable = [
        'tenant_id','staff_profile_id','module_code','module_title','provider',
        'completed_at','valid_until','score','evidence_file_id',
    ];

    protected $casts = [
        'completed_at' => 'date',
        'valid_until'  => 'date',
    ];

    public function staffProfile()
    {
        return $this->belongsTo(\App\Models\StaffProfile::class);
    }
}
