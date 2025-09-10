<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffLeaveEntitlement extends Model
{
    protected $fillable = [
        'tenant_id','staff_profile_id','period_start','period_end','annual_leave_days','sick_pay_scheme',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'annual_leave_days' => 'decimal:2',
    ];

    public function staffProfile() { return $this->belongsTo(\App\Models\StaffProfile::class); }
}
