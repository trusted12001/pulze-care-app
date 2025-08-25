<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


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
        $tenantId = (int) auth()->user()->tenant_id;

        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                // one staff profile per user within the same tenant (ignoring soft-deleted rows)
                Rule::unique('staff_profiles', 'user_id')
                    ->where(fn ($q) => $q->where('tenant_id', $tenantId)
                                         ->whereNull('deleted_at')),
            ],

            // keep your other rules here as they were
            'employment_status' => ['required','in:active,on_leave,inactive'],
            'job_title'         => ['nullable','string','max:120'],
            'work_email'        => ['nullable','email','max:150'],
            'phone'             => ['nullable','string','max:40'],
            'dbs_number'        => ['nullable','string','max:100'],
            'dbs_issued_at'     => ['nullable','date'],
            'mandatory_training_completed_at' => ['nullable','date'],
            'nmc_pin'           => ['nullable','string','max:100'],
            'gphc_pin'          => ['nullable','string','max:100'],
            'right_to_work_verified_at' => ['nullable','date'],
            'notes'             => ['nullable','string'],
        ];
    }
}
