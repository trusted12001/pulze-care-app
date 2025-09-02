<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Route middleware already guards role/tenant; this is enough here.
        return $this->user() !== null;
    }

    public function rules(): array
    {
        // We’ll use the optional {section} route param for tab-by-tab updates.
        // If you call the standard resource update (no section), we validate the full payload.
        $section      = (string) ($this->route('section') ?? '');
        $tenantId     = (int) $this->user()->tenant_id;
        $serviceUser  = $this->route('service_user'); // implicit route-model binding
        $serviceUserId = is_object($serviceUser) ? (int) $serviceUser->id : (int) $serviceUser;

        // Helper rules used in multiple sections
        $nhsUniqueScoped = Rule::unique('service_users', 'nhs_number')
            ->ignore($serviceUserId)
            ->where(fn ($q) => $q->where('tenant_id', $tenantId)->whereNull('deleted_at'));

        // If a section is provided, validate only that section’s fields (PATCH).
        // If no section is provided, fall back to "full" rules (PUT from your existing edit form).
        return match ($section) {

            // ===== Identity & Inclusion tab =====
            'identity' => [
                'gender_identity'        => ['sometimes','nullable','string','max:255'],
                'pronouns'               => ['sometimes','nullable','string','max:255'],
                // Shape check and tenant-scoped uniqueness (ignoring current record)
                'nhs_number'             => ['sometimes','nullable','string','max:255',
                    'regex:/^\s*\d{3}\s?\d{3}\s?\d{4}\s*$/', $nhsUniqueScoped
                ],
                // Common UK NI format (e.g., QQ123456C) — adjust if your homes allow training/temp numbers
                'national_insurance_no'  => ['sometimes','nullable','string','max:255',
                    'regex:/^[A-CEGHJ-PR-TW-Z]{2}\d{6}[A-D]$/i'
                ],
                'photo_path'             => ['sometimes','nullable','string','max:255'],
            ],

            // ===== Communication tab =====
            'communication' => [
                'communication_needs'    => ['sometimes','nullable','string'],
            ],

            // ===== Clinical Flags & Plans tab =====
            'clinical_flags' => [
                'behaviour_support_plan' => ['sometimes','boolean'],
                'seizure_care_plan'      => ['sometimes','boolean'],
                'diabetes_care_plan'     => ['sometimes','boolean'],
                'oxygen_therapy'         => ['sometimes','boolean'],
            ],

            // ===== Baselines tab =====
            'baselines' => [
                'baseline_bp'            => ['sometimes','nullable','string','max:255'], // e.g. "120/80"
                'baseline_hr'            => ['sometimes','nullable','string','max:255'],
                'baseline_spo2'          => ['sometimes','nullable','string','max:255'],
                'baseline_temp'          => ['sometimes','nullable','string','max:255'],
            ],

            // ===== Risks & Safeguarding tab =====
            'risks' => [
                'fall_risk'              => ['sometimes','nullable','string','max:255'],
                'choking_risk'           => ['sometimes','nullable','string','max:255'],
                'pressure_ulcer_risk'    => ['sometimes','nullable','string','max:255'],
                'wander_elopement_risk'  => ['sometimes','boolean'],
                'safeguarding_flag'      => ['sometimes','boolean'],
                'infection_control_flag' => ['sometimes','boolean'],
                'smoking_status'         => ['sometimes','nullable','string','max:255'],
                'capacity_status'        => ['sometimes','nullable','string','max:255'],
            ],

            // ===== Legal & Consent tab =====
            'legal_consent' => [
                'consent_obtained_at'    => ['sometimes','nullable','date'],
                'dnacpr_status'          => ['sometimes','nullable','string','max:255'],
                'dnacpr_review_date'     => ['sometimes','nullable','date'],
                'dols_in_place'          => ['sometimes','boolean'],
                'dols_approval_date'     => ['sometimes','nullable','date'],
                'lpa_health_welfare'     => ['sometimes','boolean'],
                'lpa_finance_property'   => ['sometimes','boolean'],
                'advanced_decision_note' => ['sometimes','nullable','string'],
            ],

            // ===== Preferences tab =====
            'preferences' => [
                'ethnicity'              => ['sometimes','nullable','string','max:255'],
                'religion'               => ['sometimes','nullable','string','max:255'],
                'primary_language'       => ['sometimes','nullable','string','max:255'],
                'interpreter_required'   => ['sometimes','boolean'],
                'personal_preferences'   => ['sometimes','nullable','string'],
                'food_preferences'       => ['sometimes','nullable','string'],
            ],

            // ===== GP & Pharmacy tab =====
            'gp_pharmacy' => [
                'gp_practice_name'       => ['sometimes','nullable','string','max:255'],
                'gp_contact_name'        => ['sometimes','nullable','string','max:255'],
                'gp_phone'               => ['sometimes','nullable','string','max:255'],
                'gp_email'               => ['sometimes','nullable','email','max:255'],
                'gp_address'             => ['sometimes','nullable','string'],
                'pharmacy_name'          => ['sometimes','nullable','string','max:255'],
                'pharmacy_phone'         => ['sometimes','nullable','string','max:255'],
            ],

            // ===== Tags tab =====
            'tags' => [
                // Current schema is a longtext column. Store JSON or comma-separated;
                // front-end can send JSON string.
                'tags'                   => ['sometimes','nullable','string'],
            ],

            // ===== Default: FULL update (your current edit form) =====
            default => [
                'first_name' => ['required','string','max:255'],
                'middle_name' => ['nullable','string','max:255'],
                'last_name'  => ['required','string','max:255'],
                'preferred_name' => ['nullable','string','max:255'],

                'date_of_birth' => ['required','date'],
                'sex_at_birth' => ['nullable','string','max:255'],
                'gender_identity' => ['nullable','string','max:255'],
                'pronouns' => ['nullable','string','max:255'],

                'nhs_number' => ['nullable','string','max:255',
                    'regex:/^\s*\d{3}\s?\d{3}\s?\d{4}\s*$/', $nhsUniqueScoped
                ],
                'national_insurance_no' => ['nullable','string','max:255',
                    'regex:/^[A-CEGHJ-PR-TW-Z]{2}\d{6}[A-D]$/i'
                ],

                'photo_path' => ['nullable','string','max:255'],

                'primary_phone' => ['nullable','string','max:255'],
                'secondary_phone' => ['nullable','string','max:255'],
                'email' => ['nullable','email','max:255'],

                'address_line1' => ['required','string','max:255'],
                'address_line2' => ['nullable','string','max:255'],
                'city' => ['required','string','max:255'],
                'county' => ['nullable','string','max:255'],
                'postcode' => ['required','string','max:255'],
                'country' => ['required','string','max:255'],

                'placement_type' => ['nullable','string','max:255'],
                'location_id' => ['nullable',
                    Rule::exists('locations','id')->where(fn($q) => $q->where('tenant_id',$tenantId))
                ],
                'room_number' => ['nullable','string','max:255'],

                'admission_date' => ['required','date'],
                'expected_discharge_date' => ['nullable','date'],
                'discharge_date' => ['nullable','date'],

                // Keep your current enum set
                'status' => ['required','in:active,inactive,discharged'],

                'funding_type' => ['nullable','string','max:255'],
                'funding_authority' => ['nullable','string','max:255'],
                'purchase_order_ref' => ['nullable','string','max:255'],
                'weekly_rate' => ['nullable','numeric','between:0,999999.99'],

                'primary_diagnosis' => ['nullable','string','max:255'],
                'other_diagnoses' => ['nullable','string'],
                'allergies_summary' => ['nullable','string'],
                'diet_type' => ['nullable','string','max:255'],
                'intolerances' => ['nullable','string'],

                'mobility_status' => ['nullable','string','max:255'],
                'communication_needs' => ['nullable','string'],

                'behaviour_support_plan' => ['boolean'],
                'seizure_care_plan' => ['boolean'],
                'diabetes_care_plan' => ['boolean'],
                'oxygen_therapy' => ['boolean'],

                'baseline_bp' => ['nullable','string','max:255'],
                'baseline_hr' => ['nullable','string','max:255'],
                'baseline_spo2' => ['nullable','string','max:255'],
                'baseline_temp' => ['nullable','string','max:255'],

                'fall_risk' => ['nullable','string','max:255'],
                'choking_risk' => ['nullable','string','max:255'],
                'pressure_ulcer_risk' => ['nullable','string','max:255'],
                'wander_elopement_risk' => ['boolean'],

                'safeguarding_flag' => ['boolean'],
                'infection_control_flag' => ['boolean'],
                'smoking_status' => ['nullable','string','max:255'],
                'capacity_status' => ['nullable','string','max:255'],

                'consent_obtained_at' => ['nullable','date'],
                'dnacpr_status' => ['nullable','string','max:255'],
                'dnacpr_review_date' => ['nullable','date'],
                'dols_in_place' => ['boolean'],
                'dols_approval_date' => ['nullable','date'],

                'lpa_health_welfare' => ['boolean'],
                'lpa_finance_property' => ['boolean'],
                'advanced_decision_note' => ['nullable','string'],

                'ethnicity' => ['nullable','string','max:255'],
                'religion' => ['nullable','string','max:255'],
                'primary_language' => ['nullable','string','max:255'],
                'interpreter_required' => ['boolean'],

                'personal_preferences' => ['nullable','string'],
                'food_preferences' => ['nullable','string'],

                'gp_practice_name' => ['nullable','string','max:255'],
                'gp_contact_name' => ['nullable','string','max:255'],
                'gp_phone' => ['nullable','string','max:255'],
                'gp_email' => ['nullable','email','max:255'],
                'gp_address' => ['nullable','string'],

                'pharmacy_name' => ['nullable','string','max:255'],
                'pharmacy_phone' => ['nullable','string','max:255'],

                'tags' => ['nullable','string'],
            ],
        };
    }
}
