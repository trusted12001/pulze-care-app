<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // Required MVP fields
            'first_name'        => ['required','string','max:100'],
            'last_name'         => ['required','string','max:100'],
            'date_of_birth'     => ['required','date'],
            'address_line1'     => ['required','string','max:255'],
            'city'              => ['required','string','max:120'],
            'postcode'          => ['required','string','max:20'],
            'admission_date'    => ['required','date'],

            // Optional fields
            'preferred_name'    => ['nullable','string','max:120'],
            'sex_at_birth'      => ['nullable','string','max:30'],
            'gender_identity'   => ['nullable','string','max:60'],
            'pronouns'          => ['nullable','string','max:40'],
            'nhs_number'        => ['nullable','string','max:20'],
            'primary_phone'     => ['nullable','string','max:40'],
            'secondary_phone'   => ['nullable','string','max:40'],
            'email'             => ['nullable','email','max:150'],
            'county'            => ['nullable','string','max:120'],
            'country'           => ['nullable','string','max:80'],

            'placement_type'    => ['nullable','string','max:40'],
            'location_id'       => ['nullable','integer'],
            'room_number'       => ['nullable','string','max:50'],
            'expected_discharge_date' => ['nullable','date','after_or_equal:admission_date'],
            'discharge_date'    => ['nullable','date'],

            'status'            => ['nullable','in:active,on_leave,discharged,deceased'],

            'weekly_rate'       => ['nullable','numeric','min:0'],

            'gp_practice_name'  => ['nullable','string','max:200'],
            'gp_contact_name'   => ['nullable','string','max:120'],
            'gp_phone'          => ['nullable','string','max:40'],
            'gp_email'          => ['nullable','email','max:150'],
            'pharmacy_name'     => ['nullable','string','max:200'],
            'pharmacy_phone'    => ['nullable','string','max:40'],

            // Booleans
            'behaviour_support_plan' => ['nullable','boolean'],
            'seizure_care_plan'      => ['nullable','boolean'],
            'diabetes_care_plan'     => ['nullable','boolean'],
            'oxygen_therapy'         => ['nullable','boolean'],
            'wander_elopement_risk'  => ['nullable','boolean'],
            'safeguarding_flag'      => ['nullable','boolean'],
            'infection_control_flag' => ['nullable','boolean'],
            'interpreter_required'   => ['nullable','boolean'],
            'dols_in_place'          => ['nullable','boolean'],
            'lpa_health_welfare'     => ['nullable','boolean'],
            'lpa_finance_property'   => ['nullable','boolean'],

            // Dates/DateTimes (optional)
            'consent_obtained_at'    => ['nullable','date'],
            'dnacpr_review_date'     => ['nullable','date'],
            'dols_approval_date'     => ['nullable','date'],
        ];
    }
}
