<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;


class UpdateStaffProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            // Keep user locked after creation
            'user_id'            => ['prohibited'],

            // Core HR
            'employment_status'  => ['required','in:active,on_leave,inactive'],
            'employment_type'    => ['required','in:employee,worker,contractor,bank,agency'],
            'engagement_basis'   => ['required','in:full_time,part_time,casual,zero_hours'],
            'job_title'          => ['nullable','string','max:120'],
            'date_of_birth'      => ['required','date','before:today'],

            // Dates
            'hire_date'          => ['nullable','date'],
            'start_in_post'      => ['nullable','date'],
            'end_in_post'        => ['nullable','date','after_or_equal:start_in_post'],

            // Structure
            'work_location_id'    => ['nullable','integer'],
            'line_manager_user_id'=> ['nullable','integer','exists:users,id'],

            // Compliance
            'dbs_number'         => ['nullable','string','max:100'],
            'dbs_issued_at'      => ['nullable','date'],
            'dbs_update_service' => ['nullable','boolean'],
            'mandatory_training_completed_at' => ['nullable','date'],
            'right_to_work_verified_at'       => ['nullable','date'],

            // Registrations
            'nmc_pin'            => ['nullable','string','max:100'],
            'gphc_pin'           => ['nullable','string','max:100'],

            // Contact
            'phone'              => ['nullable','string','max:40'],
            'work_email'         => ['nullable','email','max:190'],

            // Notes
            'notes'              => ['nullable','string'],
        ];
    }
}
