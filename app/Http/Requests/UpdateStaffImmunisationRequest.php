<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateStaffImmunisationRequest extends FormRequest
{
    public function authorize(): bool { return Auth::check(); }

    public function rules(): array
    {
        return [
            'vaccine'           => ['required','in:HepB,MMR,Varicella,TB_BCG,Flu,Covid19,Tetanus,Pertussis,Other'],
            'dose'              => ['nullable','string','max:50'],
            'administered_at'   => ['required','date'],
            'evidence_file_id'  => ['nullable','integer'],
            'notes'             => ['nullable','string'],
        ];
    }
}
