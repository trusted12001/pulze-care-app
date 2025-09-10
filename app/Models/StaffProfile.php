<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id','user_id','job_title','date_of_birth',
        'employment_status','employment_type','engagement_basis',
        'hire_date','start_in_post','end_in_post',
        'work_location_id','line_manager_user_id',
        'dbs_number','dbs_issued_at','dbs_update_service',
        'mandatory_training_completed_at','nmc_pin','gphc_pin',
        'right_to_work_verified_at','phone','work_email','notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'start_in_post' => 'date',
        'end_in_post' => 'date',
        'dbs_issued_at' => 'date',
        'mandatory_training_completed_at' => 'datetime',
        'right_to_work_verified_at' => 'datetime',
        'dbs_update_service' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lineManager()
    {
        return $this->belongsTo(User::class, 'line_manager_user_id');
    }

    public function workLocation()
    {
        // Update model/class name if your "locations" model differs
        return $this->belongsTo(\App\Models\Location::class, 'work_location_id');
    }

    // Scopes
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeActive($query)
    {
        return $query->where('employment_status', 'active');
    }

    // ...
    public function contracts()
    {
        return $this->hasMany(\App\Models\StaffContract::class);
    }

    public function registrations()
    {
        return $this->hasMany(\App\Models\StaffRegistration::class);
    }

    public function employmentChecks()
    {
        return $this->hasMany(\App\Models\StaffEmploymentCheck::class);
    }

    public function visas()
    {
        return $this->hasMany(\App\Models\StaffVisa::class);
    }



}
