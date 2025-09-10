<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffOccHealthClearance extends Model
{
    protected $table = 'staff_occhealth_clearance';

    protected $fillable = [
        'tenant_id','staff_profile_id','cleared_for_role','assessed_at','restrictions','review_due_at',
    ];

    protected $casts = [
        'cleared_for_role' => 'boolean',
        'assessed_at' => 'date',
        'review_due_at' => 'date',
    ];

    public function staffProfile() { return $this->belongsTo(\App\Models\StaffProfile::class); }
}
