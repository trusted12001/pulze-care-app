<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskControl extends Model
{
    protected $fillable = [
        'risk_assessment_id','control_type','description','mandatory',
        'evidence_type','frequency','assigned_to_user_id','assigned_to_role',
        'link_to_assignment','effectiveness_rating','is_active',
    ];

    protected $casts = [
        'mandatory' => 'boolean',
        'link_to_assignment' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function assessment() { return $this->belongsTo(RiskAssessment::class, 'risk_assessment_id'); }
    public function assignee()   { return $this->belongsTo(User::class, 'assigned_to_user_id'); }
    public function task()       { return $this->hasOne(AssignmentTask::class, 'risk_control_id'); }
}
