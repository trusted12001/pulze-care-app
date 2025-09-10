<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreStaffDrivingLicenceRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'licence_number'               => ['nullable','string','max:50'],
            'categories'                   => ['nullable','string','max:50'], // e.g., B, C1
            'expires_at'                   => ['nullable','date'],
            'business_insurance_confirmed' => ['required','boolean'],
            'document_file_id'             => ['nullable','integer'],
        ];
    }
}
