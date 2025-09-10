<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffVisa extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id','staff_profile_id',
        'status','immigration_category',
        'nationality','passport_number','passport_expires_at',
        'visa_number','brp_number','brp_expires_at','share_code',
        'sponsor_licence_number','cos_number',
        'issued_country','issued_at','expires_at',
        'weekly_hours_cap','term_time_only','restrictions',
        'evidence_file_id','checked_by_user_id','notes',
    ];

    protected $casts = [
        'passport_expires_at' => 'date',
        'brp_expires_at'      => 'date',
        'issued_at'           => 'date',
        'expires_at'          => 'date',
        'term_time_only'      => 'boolean',
        'weekly_hours_cap'    => 'decimal:1',
    ];

    public function staffProfile()
    {
        return $this->belongsTo(\App\Models\StaffProfile::class);
    }

    public function checkedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'checked_by_user_id');
    }

    public function scopeForTenant($q, $tenantId)
    {
        return $q->where('tenant_id', $tenantId);
    }
}
