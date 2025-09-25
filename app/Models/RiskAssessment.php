<?php

namespace App\Models;

use App\Enums\RiskBand;
use App\Enums\RiskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiskAssessment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'service_user_id','risk_type_id','title','context',
        'likelihood','severity','risk_score','risk_band','status',
        'next_review_date','review_frequency','created_by','approved_by','approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function serviceUser() { return $this->belongsTo(ServiceUser::class); }
    public function riskType()    { return $this->belongsTo(RiskType::class); }
    public function creator()     { return $this->belongsTo(User::class, 'created_by'); }
    public function approver()    { return $this->belongsTo(User::class, 'approved_by'); }

    // Helpers
    public function computeScore(): int
    {
        $l = max(1, min(5, (int) $this->likelihood));
        $s = max(1, min(5, (int) $this->severity));
        return $l * $s;
    }

    public function bandFromScore(int $score): RiskBand
    {
        // default 5x5: 1–5 Low, 6–12 Medium, 15–25 High
        return match (true) {
            $score >= 15 => RiskBand::High,
            $score >= 6  => RiskBand::Medium,
            default      => RiskBand::Low,
        };
    }

    public function setScoreAndBand(): void
    {
        $score = $this->computeScore();
        $this->risk_score = $score;
        $this->risk_band  = $this->bandFromScore($score)->value;
    }

    public function markApproved(int $userId): void
    {
        $this->status = RiskStatus::Active->value;
        $this->approved_by = $userId;
        $this->approved_at = now();
    }
}
