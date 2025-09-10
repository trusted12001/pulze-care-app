<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateStaffLeaveEntitlementRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'period_start' => ['required','date'],
            'period_end'   => ['required','date','after_or_equal:period_start'],
            'annual_leave_days' => ['required','numeric','min:0','max:365'],
            'sick_pay_scheme'   => ['nullable','string','max:100'],
        ];
    }
}
