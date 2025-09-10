<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreStaffAdjustmentRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'type'           => ['required','string','max:100'],
            'in_place_from'  => ['nullable','date'],
            'notes'          => ['nullable','string'],
        ];
    }
}
