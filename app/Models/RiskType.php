<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskType extends Model
{
    protected $fillable = ['name', 'default_guidance', 'default_matrix'];
    protected $casts = [
        'default_matrix' => 'array',
    ];
}
