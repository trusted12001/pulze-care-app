<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskReview extends Model
{
    protected $fillable = [
        'risk_assessment_id','reviewed_by','review_date','reason',
        'likelihood_new','severity_new','score_new','band_new',
        'recommendations','outcome',
    ];

    protected $casts = ['review_date' => 'date'];

    public function assessment() { return $this->belongsTo(RiskAssessment::class, 'risk_assessment_id'); }
    public function reviewer()   { return $this->belongsTo(User::class, 'reviewed_by'); }
}
