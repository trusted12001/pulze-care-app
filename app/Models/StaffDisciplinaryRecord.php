<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffDisciplinaryRecord extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id','staff_profile_id','type','opened_at','closed_at','summary','outcome',
    ];

    protected $casts = [
        'opened_at' => 'date',
        'closed_at' => 'date',
    ];

    public function staffProfile() {
        return $this->belongsTo(\App\Models\StaffProfile::class);
    }
}
