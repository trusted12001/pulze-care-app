<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpiryAlert extends Model
{
    protected $fillable = [
        'tenant_id',
        'staff_profile_id',
        'resource_type',
        'resource_id',
        'window_days',
        'alert_date',
    ];
}
