<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CarePlan;
use App\Models\CarePlanGoal;

class CarePlanSection extends Model
{
    protected $fillable = ['care_plan_id','name','description','display_order','is_active'];

    public function carePlan() { return $this->belongsTo(CarePlan::class); }
    public function goals()    { return $this->hasMany(CarePlanGoal::class)->orderBy('display_order'); }
}
