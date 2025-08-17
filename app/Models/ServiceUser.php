<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class ServiceUser extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id','first_name','middle_name','last_name','preferred_name',
        'date_of_birth','sex_at_birth','gender_identity','pronouns',
        'nhs_number','national_insurance_no','photo_path',
        'primary_phone','secondary_phone','email',
        'address_line1','address_line2','city','county','postcode','country',
        'placement_type','location_id','room_number','admission_date',
        'expected_discharge_date','discharge_date','status',
        'funding_type','funding_authority','purchase_order_ref','weekly_rate',
        'primary_diagnosis','other_diagnoses','allergies_summary','diet_type',
        'intolerances','mobility_status','communication_needs',
        'behaviour_support_plan','seizure_care_plan','diabetes_care_plan','oxygen_therapy',
        'baseline_bp','baseline_hr','baseline_spo2','baseline_temp',
        'fall_risk','choking_risk','pressure_ulcer_risk',
        'wander_elopement_risk','safeguarding_flag','infection_control_flag','smoking_status',
        'capacity_status','consent_obtained_at','dnacpr_status','dnacpr_review_date',
        'dols_in_place','dols_approval_date','lpa_health_welfare','lpa_finance_property','advanced_decision_note',
        'ethnicity','religion','primary_language','interpreter_required','personal_preferences','food_preferences',
        'gp_practice_name','gp_contact_name','gp_phone','gp_email','gp_address','pharmacy_name','pharmacy_phone',
        'created_by','updated_by','tags',
    ];

    protected $casts = [
        'date_of_birth'                  => 'date',
        'admission_date'                 => 'date',
        'expected_discharge_date'        => 'date',
        'discharge_date'                 => 'date',
        'weekly_rate'                    => 'decimal:2',
        'behaviour_support_plan'         => 'boolean',
        'seizure_care_plan'              => 'boolean',
        'diabetes_care_plan'             => 'boolean',
        'oxygen_therapy'                 => 'boolean',
        'wander_elopement_risk'          => 'boolean',
        'safeguarding_flag'              => 'boolean',
        'infection_control_flag'         => 'boolean',
        'interpreter_required'           => 'boolean',
        'consent_obtained_at'            => 'datetime',
        'dnacpr_review_date'             => 'date',
        'dols_in_place'                  => 'boolean',
        'dols_approval_date'             => 'date',
        'lpa_health_welfare'             => 'boolean',
        'lpa_finance_property'           => 'boolean',
        'tags'                           => 'array',
    ];

    // Convenience Accessors
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.($this->middle_name ? $this->middle_name.' ' : '').$this->last_name);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null;
    }

    // Relationships
    public function location()
    {
        return $this->belongsTo(\App\Models\Location::class, 'location_id'); // if you have one
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}
