<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarePlanVersion extends Model
{
    protected $fillable = [
        'care_plan_id','version','approved_by','approved_at','change_note','snapshot'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'snapshot' => 'array',
    ];

    public function carePlan() { return $this->belongsTo(CarePlan::class); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }
}
