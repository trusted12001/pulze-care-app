<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentTask extends Model
{
    protected $fillable = [
        'title','service_user_id','assignee_user_id','risk_control_id',
        'frequency','due_at','status','completed_at','completed_by',
        'evidence_type','evidence_path',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function control() { return $this->belongsTo(RiskControl::class, 'risk_control_id'); }
    public function serviceUser() { return $this->belongsTo(ServiceUser::class, 'service_user_id'); }
    public function assignee() { return $this->belongsTo(User::class, 'assignee_user_id'); }
    public function completer() { return $this->belongsTo(User::class, 'completed_by'); }
}
