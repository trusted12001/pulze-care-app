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
        'mandatory'            => 'boolean',
        'link_to_assignment'   => 'boolean',
        'is_active'            => 'boolean',
        'effectiveness_rating' => 'integer',
    ];

    /**
     * When a control changes, reflect it on the parent assessment's updated_at.
     */
    protected $touches = ['assessment'];

    // Relationships
    public function assessment() { return $this->belongsTo(RiskAssessment::class, 'risk_assessment_id'); }
    public function assignee()   { return $this->belongsTo(User::class, 'assigned_to_user_id'); }
    public function task()       { return $this->hasOne(AssignmentTask::class, 'risk_control_id'); }

    // Scopes
    public function scopeActive($q)      { return $q->where('is_active', true); }
    public function scopeMandatory($q)   { return $q->where('mandatory', true); }
    public function scopeLinked($q)      { return $q->where('link_to_assignment', true); }

    // QoL: display who/what this is assigned to
    public function getAssigneeLabelAttribute(): string
    {
        return $this->assigned_to_role ?? ($this->assignee?->name ?? '—');
    }
}
