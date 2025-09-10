<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffSupervisionAppraisal extends Model
{
    protected $table = 'staff_supervisions_appraisals';

    protected $fillable = [
        'tenant_id','staff_profile_id','type','held_at',
        'manager_user_id','summary','next_due_at',
    ];

    protected $casts = [
        'held_at' => 'date',
        'next_due_at' => 'date',
    ];

    public function staffProfile() { return $this->belongsTo(\App\Models\StaffProfile::class); }
    public function manager() { return $this->belongsTo(\App\Models\User::class, 'manager_user_id'); }
}
