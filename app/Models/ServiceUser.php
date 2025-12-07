<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceUser extends Model
{
    use SoftDeletes;

    // ✅ Important: match your actual table name
    protected $table = 'service_users';

    protected $fillable = [
        'tenant_id',
        'first_name',
        'middle_name',
        'last_name',
        'preferred_name',
        'date_of_birth',
        'sex_at_birth',
        'gender_identity',
        'pronouns',
        'nhs_number',
        'national_insurance_no',
        'photo_path',
        'primary_phone',
        'secondary_phone',
        'email',
        'address_line1',
        'address_line2',
        'city',
        'county',
        'postcode',
        'country',
        'placement_type',
        'location_id',
        'room_number',
        'admission_date',
        'expected_discharge_date',
        'discharge_date',
        'status',
        'funding_type',
        'funding_authority',
        'purchase_order_ref',
        'weekly_rate',
        'primary_diagnosis',
        'other_diagnoses',
        'allergies_summary',
        'diet_type',
        'intolerances',
        'mobility_status',
        'communication_needs',
        'behaviour_support_plan',
        'seizure_care_plan',
        'diabetes_care_plan',
        'oxygen_therapy',
        'baseline_bp',
        'baseline_hr',
        'baseline_spo2',
        'baseline_temp',
        'fall_risk',
        'choking_risk',
        'pressure_ulcer_risk',
        'wander_elopement_risk',
        'safeguarding_flag',
        'infection_control_flag',
        'smoking_status',
        'capacity_status',
        'consent_obtained_at',
        'dnacpr_status',
        'dnacpr_review_date',
        'dols_in_place',
        'dols_approval_date',
        'lpa_health_welfare',
        'lpa_finance_property',
        'advanced_decision_note',
        'ethnicity',
        'religion',
        'primary_language',
        'interpreter_required',
        'personal_preferences',
        'food_preferences',
        'gp_practice_name',
        'gp_contact_name',
        'gp_phone',
        'gp_email',
        'gp_address',
        'pharmacy_name',
        'pharmacy_phone',
        'created_by',
        'updated_by',
        'tags',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
        'expected_discharge_date' => 'date',
        'discharge_date' => 'date',
        'consent_obtained_at' => 'datetime',
        'dnacpr_review_date' => 'date',
        'dols_approval_date' => 'date',
        'weekly_rate' => 'decimal:2',
        'behaviour_support_plan' => 'boolean',
        'seizure_care_plan' => 'boolean',
        'diabetes_care_plan' => 'boolean',
        'oxygen_therapy' => 'boolean',
        'wander_elopement_risk' => 'boolean',
        'safeguarding_flag' => 'boolean',
        'infection_control_flag' => 'boolean',
        'dols_in_place' => 'boolean',
        'lpa_health_welfare' => 'boolean',
        'lpa_finance_property' => 'boolean',
        'interpreter_required' => 'boolean',
        // 👉 If `tags` is JSON in DB, uncomment:
        // 'tags' => 'array',
    ];

    // Relationships
    public function location()
    {
        return $this->belongsTo(\App\Models\Location::class)->withDefault();
    }
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by')->withDefault();
    }
    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by')->withDefault();
    }

    // 🔗 Add this so Risk Assessments can be fetched from a Service User
    public function riskAssessments()
    {
        return $this->hasMany(\App\Models\RiskAssessment::class);
    }

    // Helpers
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name);
    }


    // Relationships for documents (polymorphic)
    public function documents()
    {
        return $this->morphMany(\App\Models\Document::class, 'owner');
    }

    /**
     * Helper relationship for Passport Photo documents.
     *
     * Returns a query ordered so that ->first() gets the latest Passport Photo.
     */
    public function passportPhoto()
    {
        return $this->documents()
            ->where('category', 'Passport Photo')
            ->latest('id');
    }

    /**
     * Convenience accessor: $serviceUser->passport_photo_url
     *
     * Returns the publicly accessible URL of the latest Passport Photo document,
     * or null if none exists.
     */
    public function getPassportPhotoUrlAttribute(): ?string
    {
        // Prefer using an already loaded relation to avoid an extra query
        if ($this->relationLoaded('passportPhoto')) {
            $doc = $this->passportPhoto;
            // If it's a collection (e.g. due to how it's loaded), pick first
            if ($doc instanceof \Illuminate\Support\Collection) {
                $doc = $doc->first();
            }
        } else {
            $doc = $this->passportPhoto()->first();
        }

        return $doc?->url;
    }

    // Optional handy helper (useful in views)
    // public function getAgeAttribute(): ?int
    // {
    //     return $this->date_of_birth ? $this->date_of_birth->age : null;
    // }
}
