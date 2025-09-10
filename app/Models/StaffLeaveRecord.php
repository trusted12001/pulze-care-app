<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffLeaveRecord extends Model
{
    protected $fillable = [
        'tenant_id','staff_profile_id','type','start_date','end_date','hours','reason','fit_note_file_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'hours' => 'decimal:2',
    ];

    public function staffProfile() { return $this->belongsTo(\App\Models\StaffProfile::class); }
}
