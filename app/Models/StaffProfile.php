<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id','user_id','job_title','date_of_birth',
        'employment_status','employment_type','engagement_basis',
        'hire_date','start_in_post','end_in_post',
        'work_location_id','line_manager_user_id',
        'dbs_number','dbs_issued_at','dbs_update_service',
        'mandatory_training_completed_at','nmc_pin','gphc_pin',
        'right_to_work_verified_at','phone','work_email','notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'start_in_post' => 'date',
        'end_in_post' => 'date',
        'dbs_issued_at' => 'date',
        'mandatory_training_completed_at' => 'datetime',
        'right_to_work_verified_at' => 'datetime',
        'dbs_update_service' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lineManager()
    {
        return $this->belongsTo(User::class, 'line_manager_user_id');
    }

    public function workLocation()
    {
        // Update model/class name if your "locations" model differs
        return $this->belongsTo(\App\Models\Location::class, 'work_location_id');
    }

    // Scopes
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeActive($query)
    {
        return $query->where('employment_status', 'active');
    }

    public function contracts()
    {
        return $this->hasMany(\App\Models\StaffContract::class);
    }

    public function registrations()
    {
        return $this->hasMany(\App\Models\StaffRegistration::class);
    }

    public function employmentChecks()
    {
        return $this->hasMany(\App\Models\StaffEmploymentCheck::class);
    }

    public function visas()
    {
        return $this->hasMany(\App\Models\StaffVisa::class);
    }

    // Payroll / Bank
    public function payroll() { return $this->hasOne(\App\Models\StaffPayroll::class); }
    public function bankAccounts() { return $this->hasMany(\App\Models\StaffBankAccount::class); }

    // Training & Supervisions
    public function trainingRecords() { return $this->hasMany(\App\Models\StaffTrainingRecord::class); }
    public function supervisionsAppraisals() { return $this->hasMany(\App\Models\StaffSupervisionAppraisal::class); }

    // Qualifications
    public function qualifications() { return $this->hasMany(\App\Models\StaffQualification::class); }

    // Occ Health & Immunisations
    public function occHealthClearances() { return $this->hasMany(\App\Models\StaffOccHealthClearance::class); }
    public function immunisations() { return $this->hasMany(\App\Models\StaffImmunisation::class); }

    // Leave & Availability
    public function leaveEntitlements() { return $this->hasMany(\App\Models\StaffLeaveEntitlement::class); }
    public function leaveRecords() { return $this->hasMany(\App\Models\StaffLeaveRecord::class); }
    public function availabilityPreferences() { return $this->hasMany(\App\Models\StaffAvailabilityPreference::class); }

    // Emergency / EDI / Adjustments / Driving
    public function emergencyContacts() { return $this->hasMany(\App\Models\StaffEmergencyContact::class); }
    public function equalityData() { return $this->hasOne(\App\Models\StaffEqualityData::class); }
    public function adjustments() { return $this->hasMany(\App\Models\StaffAdjustment::class); }
    public function drivingLicences() { return $this->hasMany(\App\Models\StaffDrivingLicence::class); }

    // Disciplinary
    public function disciplinaryRecords() { return $this->hasMany(\App\Models\StaffDisciplinaryRecord::class); }

    // Documents (polymorphic)
    public function documents()
    {
        return $this->morphMany(\App\Models\Document::class, 'owner', 'owner_type', 'owner_id');
    }



}
