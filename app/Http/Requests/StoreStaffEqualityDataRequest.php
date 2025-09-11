<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreStaffEqualityDataRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'ethnicity'         => ['nullable','string','max:100'],
            'religion'          => ['nullable','string','max:100'],
            'disability'        => ['required','boolean'],
            'gender_identity'   => ['nullable','string','max:100'],
            'sexual_orientation'=> ['nullable','string','max:100'],
            'data_source'       => ['required','in:self_declared,not_provided'],
        ];
    }
}
