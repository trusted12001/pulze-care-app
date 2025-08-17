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
        'tenant_id','user_id','job_title','employment_status',
        'dbs_number','dbs_issued_at','mandatory_training_completed_at',
        'nmc_pin','gphc_pin','right_to_work_verified_at',
        'phone','work_email','notes',
    ];

    protected $casts = [
        'dbs_issued_at' => 'date',
        'mandatory_training_completed_at' => 'datetime',
        'right_to_work_verified_at' => 'datetime',
    ];

    // relationships
    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function user()   { return $this->belongsTo(User::class);   }

    // tenant scoping helper
    public function scopeForTenant(Builder $q, int $tenantId): Builder
    {
        return $q->where('tenant_id', $tenantId);
    }
}
