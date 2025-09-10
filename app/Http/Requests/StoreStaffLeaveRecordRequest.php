<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreStaffLeaveRecordRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'type' => ['required','in:annual,sick,unpaid,maternity,paternity,compassionate,study,other'],
            'start_date' => ['required','date'],
            'end_date' => ['required','date','after_or_equal:start_date'],
            'hours' => ['nullable','numeric','min:0','max:999.99'],
            'reason' => ['nullable','string'],
            'fit_note_file_id' => ['nullable','integer'],
        ];
    }
}
