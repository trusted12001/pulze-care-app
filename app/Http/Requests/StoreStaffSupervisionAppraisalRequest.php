<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreStaffSupervisionAppraisalRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'type'            => ['required','in:supervision,appraisal,probation_review'],
            'held_at'         => ['required','date'],
            'manager_user_id' => ['nullable','integer','exists:users,id'],
            'summary'         => ['required','string'],
            'next_due_at'     => ['nullable','date','after_or_equal:held_at'],
        ];
    }
}
