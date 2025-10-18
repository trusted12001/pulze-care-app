<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarePlanSignoff extends Model
{
    // Table will default to "care_plan_signoffs" (good). Set $table if you used a different name.
    // protected $table = 'care_plan_signoffs';

    protected $fillable = [
        'care_plan_id',
        'user_id',
        'role_label',
        'pin_last4',     // store only last 4 (optional)
        'signed_at',
        'ip_address',
        'device',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    // Relationships
    public function carePlan() { return $this->belongsTo(\App\Models\CarePlan::class, 'care_plan_id'); }
    public function user()     { return $this->belongsTo(\App\Models\User::class, 'user_id'); }

    // Optional: ensure we only persist last 4 digits and trim input
    public function setPinLast4Attribute($value): void
    {
        if (is_null($value) || $value === '') {
            $this->attributes['pin_last4'] = null;
            return;
        }
        $v = preg_replace('/\D+/', '', (string) $value);
        $this->attributes['pin_last4'] = substr($v, -4); // last 4 only
    }
}
