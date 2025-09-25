<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarePlanGoal extends Model
{
    protected $fillable = ['care_plan_section_id','title','success_criteria','target_date','status','display_order'];

    protected $casts = ['target_date' => 'date'];

    public function section() { return $this->belongsTo(CarePlanSection::class, 'care_plan_section_id'); }
    public function interventions() { return $this->hasMany(CarePlanIntervention::class, 'care_plan_goal_id'); }
}
