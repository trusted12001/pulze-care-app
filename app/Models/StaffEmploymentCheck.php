<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffEmploymentCheck extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'staff_profile_id',
        'check_type',
        'result',
        'reference_no',
        'issued_at',
        'expires_at',
        'evidence_file_id',
        'checked_by_user_id',
        'notes',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'expires_at' => 'date',
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
