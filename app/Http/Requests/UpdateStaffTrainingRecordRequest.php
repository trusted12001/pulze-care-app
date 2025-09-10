<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateStaffTrainingRecordRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'module_code'     => ['required','string','max:100'],
            'module_title'    => ['required','string','max:255'],
            'provider'        => ['nullable','string','max:255'],
            'completed_at'    => ['nullable','date'],
            'valid_until'     => ['nullable','date','after_or_equal:completed_at'],
            'score'           => ['nullable','integer','min:0','max:100'],
            'evidence_file_id'=> ['nullable','integer'],
        ];
    }
}
