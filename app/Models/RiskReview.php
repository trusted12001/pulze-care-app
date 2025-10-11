<?php

namespace App\Models;

use App\Enums\RiskBand;
use Illuminate\Database\Eloquent\Model;

class RiskReview extends Model
{
    protected $fillable = [
        'risk_assessment_id','reviewed_by','review_date','reason',
        'likelihood_new','severity_new','score_new','band_new',
        'recommendations','outcome',
    ];

    protected $casts = [
        'review_date'    => 'date',
        'likelihood_new' => 'integer',
        'severity_new'   => 'integer',
        'score_new'      => 'integer',
    ];

    /**
     * Touch parent so assessment.updated_at reflects review activity.
     */
    protected $touches = ['assessment'];

    // Relationships
    public function assessment() { return $this->belongsTo(RiskAssessment::class, 'risk_assessment_id'); }
    public function reviewer()   { return $this->belongsTo(User::class, 'reviewed_by'); }

    // Scopes
    public function scopeWithRescore($q)
    {
        return $q->whereNotNull('likelihood_new')
                 ->whereNotNull('severity_new');
    }

    // Helpers
    public function computeScore(): ?int
    {
        if ($this->likelihood_new === null || $this->severity_new === null) {
            return null;
        }
        $l = max(1, min(5, (int) $this->likelihood_new));
        $s = max(1, min(5, (int) $this->severity_new));
        return $l * $s;
    }

    public function bandFromScore(int $score): string
    {
        return match (true) {
            $score >= 15 => RiskBand::High->value,
            $score >= 6  => RiskBand::Medium->value,
            default      => RiskBand::Low->value,
        };
    }

    /**
     * If L/S provided but score/band missing, fill them automatically on save.
     */
    protected static function booted(): void
    {
        static::saving(function (self $model) {
            if ($model->likelihood_new !== null && $model->severity_new !== null) {
                $score = $model->computeScore();
                if ($score !== null) {
                    $model->score_new ??= $score;
                    $model->band_new  ??= $model->bandFromScore($score);
                }
            }
        });
    }
}
