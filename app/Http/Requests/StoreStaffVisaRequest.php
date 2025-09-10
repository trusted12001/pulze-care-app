<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreStaffVisaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'status'               => ['required','in:active,expired,pending,revoked'],
            'immigration_category' => ['required','in:settled,pre_settled,skilled_worker,student,family,british,irish,other'],

            'nationality'          => ['nullable','string','max:100'],
            'passport_number'      => ['nullable','string','max:50'],
            'passport_expires_at'  => ['nullable','date'],

            'visa_number'          => ['nullable','string','max:100'],
            'brp_number'           => ['nullable','string','max:50'],
            'brp_expires_at'       => ['nullable','date'],
            'share_code'           => ['nullable','string','max:20'],
            'sponsor_licence_number'=> ['nullable','string','max:50'],
            'cos_number'           => ['nullable','string','max:50'],

            'issued_country'       => ['nullable','string','max:100'],
            'issued_at'            => ['nullable','date'],
            'expires_at'           => ['nullable','date','after_or_equal:issued_at'],

            'weekly_hours_cap'     => ['nullable','numeric','min:0','max:168'],
            'term_time_only'       => ['nullable','boolean'],
            'restrictions'         => ['nullable','string'],

            'evidence_file_id'     => ['nullable','integer'],
            'checked_by_user_id'   => ['nullable','integer','exists:users,id'],
            'notes'                => ['nullable','string'],
        ];
    }
}
