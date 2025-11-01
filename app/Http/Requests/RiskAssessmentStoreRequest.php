<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RiskAssessmentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create risk assessments') ?? true; // adjust to your policy/roles
    }

    public function rules(): array
    {
        return [
            'service_user_id'  => ['required','exists:service_users,id'],
            'title'            => ['required','string','max:255'],
            'start_date'       => ['nullable','date'],
            'next_review_date' => ['nullable','date','after_or_equal:start_date'],
            'review_frequency' => ['nullable','string','max:100'],
            'summary'          => ['nullable','string'],
            'action'           => ['nullable','in:save,save_draft,publish'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_user_id.required' => 'Please select a service user.',
            'service_user_id.exists'   => 'Selected service user was not found.',
        ];
    }
}
