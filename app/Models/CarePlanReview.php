<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarePlanReview extends Model
{
    protected $fillable = [
        'care_plan_id','reviewed_by','review_date','reason','summary',
        'next_review_date_suggested','review_frequency_suggested',
    ];

    protected $casts = [
        'review_date' => 'date',
        'next_review_date_suggested' => 'date',
    ];

    public function carePlan() { return $this->belongsTo(CarePlan::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
}
