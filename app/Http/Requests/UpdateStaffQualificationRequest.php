<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateStaffQualificationRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'level'               => ['required','string','max:50'],
            'title'               => ['required','string','max:255'],
            'institution'         => ['nullable','string','max:255'],
            'awarded_at'          => ['nullable','date'],
            'certificate_file_id' => ['nullable','integer'],
        ];
    }
}
