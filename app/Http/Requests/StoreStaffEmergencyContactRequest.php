<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreStaffEmergencyContactRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'name'        => ['required','string','max:255'],
            'relationship'=> ['required','string','max:100'],
            'phone'       => ['required','string','max:50'],
            'email'       => ['nullable','email','max:255'],
            'address'     => ['nullable','string'],
        ];
    }
}
