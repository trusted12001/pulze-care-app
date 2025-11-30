<?php

namespace App\Http\Requests\Assignment;

use Illuminate\Foundation\Http\FormRequest;

class VerifyAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('assignments.verify');
    }
    public function rules(): array
    {
        return ['comment' => 'nullable|string'];
    }
}
