<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiskAssessmentProfile extends Model
{
    use SoftDeletes;

    public const STATUS_DRAFT    = 'draft';
    public const STATUS_ACTIVE   = 'active';
    public const STATUS_ARCHIVED = 'archived';

    protected $table = 'risk_assessment_profiles';

    protected $fillable = [
        'service_user_id',
        'title',
        'status',
        'start_date',
        'next_review_date',
        'review_frequency',
        'summary',
        'created_by_id',
        'updated_by_id',
    ];

    protected $casts = [
        'start_date'       => 'date',
        'next_review_date' => 'date',
    ];

    // Relationships
    public function serviceUser()
    {
        return $this->belongsTo(ServiceUser::class);
    }

    public function assessments() // individual risk items
    {
        return $this->hasMany(RiskAssessment::class, 'risk_assessment_profile_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function profile()
    {
        return $this->belongsTo(RiskAssessmentProfile::class, 'risk_assessment_profile_id');
    }
}
