<?php

namespace App\Http\Requests\Assignment;

use Illuminate\Foundation\Http\FormRequest;

class StartAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'accuracy' => 'nullable|numeric'
        ];
    }
}
