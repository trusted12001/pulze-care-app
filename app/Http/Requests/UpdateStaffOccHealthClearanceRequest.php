<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateStaffOccHealthClearanceRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'cleared_for_role' => ['required','boolean'],
            'assessed_at'      => ['required','date'],
            'restrictions'     => ['nullable','string'],
            'review_due_at'    => ['nullable','date','after_or_equal:assessed_at'],
        ];
    }
}
