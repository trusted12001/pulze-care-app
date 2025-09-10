<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreStaffContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'contract_ref'   => ['nullable','string','max:100'],
            'contract_type'  => ['required','in:permanent,fixed_term,bank,casual,agency'],
            'hours_per_week' => ['nullable','numeric','min:0','max:99.99'],
            'wtd_opt_out'    => ['nullable','boolean'],
            'start_date'     => ['required','date'],
            'end_date'       => ['nullable','date','after_or_equal:start_date'],
            'job_grade_band' => ['nullable','string','max:50'],
            'pay_scale'      => ['nullable','string','max:50'],
            'fte_salary'     => ['nullable','numeric','min:0'],
            'hourly_rate'    => ['nullable','numeric','min:0'],
            'cost_centre'    => ['nullable','string','max:50'],
            'notes'          => ['nullable','string'],
        ];
    }
}
