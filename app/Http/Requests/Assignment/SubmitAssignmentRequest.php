<?php

namespace App\Http\Requests\Assignment;

use Illuminate\Foundation\Http\FormRequest;

class SubmitAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'answers' => 'array',
            'answers.*' => 'nullable',
            'note' => 'nullable|string',
            'photos.*' => 'file|mimes:jpg,jpeg,png,heic,webp,pdf|max:10240',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'accuracy' => 'nullable|numeric'
        ];
    }
}
