<?php

namespace App\Http\Requests\Assignment;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|in:care,documentation,operations,training',
            'priority' => 'nullable|in:low,medium,high',
            'location_id' => 'required|exists:locations,id',
            'resident_id' => 'nullable|exists:service_users,id',
            'assigned_to' => 'required|exists:users,id',
            'window_start' => 'nullable|date',
            'window_end' => 'nullable|date|after_or_equal:window_start',
            'due_at' => 'nullable|date',
            'requires_gps' => 'boolean',
            'requires_signature' => 'boolean',
            'requires_photo' => 'boolean',
            'sla_minutes' => 'nullable|integer|min:0',
            'recurrence_rule' => 'nullable|string',
        ];
    }
}
