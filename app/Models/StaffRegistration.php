<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffRegistration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'staff_profile_id',
        'body',
        'pin_number',
        'status',
        'first_registered_at',
        'expires_at',
        'revalidation_due_at',
        'notes',
        'evidence_file_id',
    ];

    protected $casts = [
        'first_registered_at' => 'date',
        'expires_at'          => 'date',
        'revalidation_due_at' => 'date',
    ];

    public function staffProfile()
    {
        return $this->belongsTo(StaffProfile::class);
    }
}
