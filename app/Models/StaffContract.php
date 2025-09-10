<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffContract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'staff_profile_id',
        'contract_ref',
        'contract_type',
        'hours_per_week',
        'wtd_opt_out',
        'start_date',
        'end_date',
        'job_grade_band',
        'pay_scale',
        'fte_salary',
        'hourly_rate',
        'cost_centre',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'wtd_opt_out'=> 'boolean',
        'hours_per_week' => 'decimal:2',
        'fte_salary'     => 'decimal:2',
        'hourly_rate'    => 'decimal:2',
    ];

    public function staffProfile()
    {
        return $this->belongsTo(StaffProfile::class);
    }
}
