<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffBankAccount extends Model
{
    protected $fillable = [
        'tenant_id','staff_profile_id',
        'account_holder','sort_code','account_number','building_society_roll',
    ];

    protected $casts = [
        'sort_code' => 'encrypted',
        'account_number' => 'encrypted',
        'building_society_roll' => 'encrypted',
    ];

    public function staffProfile()
    {
        return $this->belongsTo(\App\Models\StaffProfile::class);
    }
}
