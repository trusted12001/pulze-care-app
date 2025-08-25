<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tenantId = (int) auth()->user()->tenant_id;
        $serviceUser = $this->route('service_user'); // implicit route model binding

        return [
            'first_name' => ['required','string','max:255'],
            'middle_name' => ['nullable','string','max:255'],
            'last_name'  => ['required','string','max:255'],
            'preferred_name' => ['nullable','string','max:255'],

            'date_of_birth' => ['required','date'],
            'sex_at_birth' => ['nullable','string','max:255'],
            'gender_identity' => ['nullable','string','max:255'],
            'pronouns' => ['nullable','string','max:255'],

            'nhs_number' => ['nullable','string','max:255',
                Rule::unique('service_users','nhs_number')
                    ->ignore($serviceUser->id)
                    ->where(fn($q) => $q->where('tenant_id',$tenantId)->whereNull('deleted_at'))
            ],
            'national_insurance_no' => ['nullable','string','max:255'],

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
        ];
    }
}
