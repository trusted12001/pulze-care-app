<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffPayroll extends Model
{
    protected $table = 'staff_payroll';

    protected $fillable = [
        'tenant_id','staff_profile_id',
        'ni_number','tax_code','starter_declaration',
        'student_loan_plan','postgrad_loan','payroll_number',
    ];

    protected $casts = [
        'ni_number' => 'encrypted',
        'postgrad_loan' => 'boolean',
    ];

    public function staffProfile()
    {
        return $this->belongsTo(\App\Models\StaffProfile::class);
    }
}
