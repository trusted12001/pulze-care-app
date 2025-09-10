<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreStaffEmploymentCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'check_type'   => ['required','in:dbs_basic,dbs_enhanced,dbs_adult_first,barred_list_adult,barred_list_child,rtw_passport,rtw_share_code,proof_of_address,reference,oh_clearance'],
            'result'       => ['required','in:pass,fail,pending'],
            'reference_no' => ['nullable','string','max:120'],
            'issued_at'    => ['nullable','date'],
            'expires_at'   => ['nullable','date','after_or_equal:issued_at'],
            'evidence_file_id'   => ['nullable','integer'],
            'checked_by_user_id' => ['nullable','integer','exists:users,id'],
            'notes'        => ['nullable','string'],
        ];
    }
}
