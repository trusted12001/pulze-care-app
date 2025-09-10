<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreStaffRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'body'               => ['required','in:NMC,HCPC,GMC,GPhC,SWE,Other'],
            'pin_number'         => ['nullable','string','max:120'],
            'status'             => ['required','in:active,lapsed,suspended,pending'],
            'first_registered_at'=> ['nullable','date'],
            'expires_at'         => ['nullable','date','after_or_equal:first_registered_at'],
            'revalidation_due_at'=> ['nullable','date'],
            'notes'              => ['nullable','string'],
            'evidence_file_id'   => ['nullable','integer'],
        ];
    }
}
