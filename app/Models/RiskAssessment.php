<?php

namespace App\Models;

use App\Enums\RiskBand;
use App\Enums\RiskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiskAssessment extends Model
{
    use SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'risk_assessment_profile_id',
        'risk_type_id',
        'context',
        'likelihood',
        'severity',
        'risk_score',
        'risk_band',
        'status',
        'next_review_date',
        'review_frequency',
        'created_by',
        'approved_by',
        'approved_at',
        'hazard',
        'controls',
        'residual_likelihood',
        'residual_severity',
        'residual_score',
        'owner_id',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'next_review_date' => 'date',
        'likelihood' => 'integer',
        'severity' => 'integer',
        'risk_score' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function serviceUser()
    {
        return $this->belongsTo(ServiceUser::class);
    }
    public function riskType()
    {
        return $this->belongsTo(RiskType::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // ✅ Added: fix RelationNotFoundException
    public function controls()
    {
        return $this->hasMany(RiskControl::class, 'risk_assessment_id');
    }
    public function reviews()
    {
        return $this->hasMany(RiskReview::class, 'risk_assessment_id')->latest();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes & Computed flags
    |--------------------------------------------------------------------------
    */
    public function scopeOverdue($q)
    {
        return $q->whereNotNull('next_review_date')
            ->whereDate('next_review_date', '<', now()->toDateString());
    }

    public function getIsOverdueAttribute(): bool
    {
        return !is_null($this->next_review_date)
            && now()->startOfDay()->gt($this->next_review_date);
    }

    public function getOverdueDaysAttribute(): ?int
    {
        return $this->is_overdue
            ? $this->next_review_date->diffInDays(now()->startOfDay())
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers (unchanged + a couple extras)
    |--------------------------------------------------------------------------
    */
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
            $score >= 6 => RiskBand::Medium,
            default => RiskBand::Low,
        };
    }

    public function setScoreAndBand(): void
    {
        $score = $this->computeScore();
        $this->risk_score = $score;
        $this->risk_band = $this->bandFromScore($score)->value;
    }

    public function markApproved(int $userId): void
    {
        $this->status = RiskStatus::Active->value;
        $this->approved_by = $userId;
        $this->approved_at = now();
    }

    // Convenience: rescore with new L/S
    public function rescore(int $likelihood, int $severity): void
    {
        $this->likelihood = $likelihood;
        $this->severity = $severity;
        $this->setScoreAndBand();
    }

    /*
    |--------------------------------------------------------------------------
    | Model Events
    |--------------------------------------------------------------------------
    | Keep risk_score / risk_band always consistent with likelihood & severity.
    */
    protected static function booted(): void
    {
        static::saving(function (self $model) {
            $model->setScoreAndBand();
        });
    }
}
