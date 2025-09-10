<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreStaffPayrollRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'ni_number'          => ['required','string','max:15'],
            'tax_code'           => ['nullable','string','max:10'],
            'starter_declaration'=> ['nullable','in:a,b,c'],
            'student_loan_plan'  => ['required','in:none,plan1,plan2,plan4,plan5'],
            'postgrad_loan'      => ['required','boolean'],
            'payroll_number'     => ['nullable','string','max:50'],
        ];
    }
}
