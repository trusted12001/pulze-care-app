<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskType extends Model
{
    protected $table = 'risk_types';

    // Keep your existing columns and (optionally) add 'description' if you want it
    protected $fillable = [
        'name',
        'default_guidance',   // long text with tips for assessors
        'default_matrix',     // JSON (e.g., scoring grid or thresholds)
        // 'description',      // <-- include only if you add this column in DB
    ];

    protected $casts = [
        'default_matrix' => 'array',
    ];

    // Link to individual risk items
    public function riskAssessments()
    {
        return $this->hasMany(RiskAssessment::class, 'risk_type_id');
    }
}
