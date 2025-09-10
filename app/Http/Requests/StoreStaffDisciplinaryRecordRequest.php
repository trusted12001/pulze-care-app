<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreStaffDisciplinaryRecordRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'type'      => ['required','in:informal,formal,warning,dismissal'],
            'opened_at' => ['required','date'],
            'closed_at' => ['nullable','date','after_or_equal:opened_at'],
            'summary'   => ['required','string'],
            'outcome'   => ['nullable','string'],
        ];
    }
}
