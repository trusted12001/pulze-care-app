<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class StoreStaffProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required','exists:users,id','unique:staff_profiles,user_id'],
            'job_title' => ['nullable','string','max:120'],
            'employment_status' => ['required','in:active,on_leave,terminated'],
            'dbs_number' => ['nullable','string','max:50'],
            'dbs_issued_at' => ['nullable','date'],
            'mandatory_training_completed_at' => ['nullable','date'],
            'nmc_pin' => ['nullable','string','max:50'],
            'gphc_pin' => ['nullable','string','max:50'],
            'right_to_work_verified_at' => ['nullable','date'],
            'phone' => ['nullable','string','max:40'],
            'work_email' => ['nullable','email','max:190'],
            'notes' => ['nullable','string'],
        ];
    }
}
