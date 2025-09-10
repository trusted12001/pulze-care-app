<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreStaffBankAccountRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'account_holder'        => ['required','string','max:255'],
            'sort_code'             => ['required','string','max:20'],
            'account_number'        => ['required','string','max:20'],
            'building_society_roll' => ['nullable','string','max:50'],
        ];
    }
}
