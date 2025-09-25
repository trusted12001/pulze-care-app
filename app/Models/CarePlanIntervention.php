<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarePlanIntervention extends Model
{
    protected $fillable = [
        'care_plan_goal_id','description','frequency',
        'assigned_to_user_id','assigned_to_role',
        'link_to_assignment','is_active',
    ];

    protected $casts = [
        'link_to_assignment' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function goal() { return $this->belongsTo(CarePlanGoal::class, 'care_plan_goal_id'); }
    public function assignee() { return $this->belongsTo(User::class, 'assigned_to_user_id'); }
}
