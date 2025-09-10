<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreStaffAvailabilityPreferenceRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'day_of_week'   => ['required','integer','min:0','max:6'], // 0=Sun .. 6=Sat
            'available_from'=> ['nullable','date_format:H:i'],
            'available_to'  => ['nullable','date_format:H:i'],
            'preference'    => ['required','in:prefer,okay,avoid'],
        ];
    }
}
