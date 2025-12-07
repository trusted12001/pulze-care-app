<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Routes are already protected by auth/role middleware
        return true;
    }

    protected function allowedCategories(): array
    {
        return [
            'Passport Photo',
            'DBS',
            'Visa',
            'Training Cert',
            'Contract',
            'Payroll',
            'Reference',
            'OH',
            'ID',
            'Other',
        ];
    }

    public function rules(): array
    {
        return [
            'category' => [
                'required',
                'string',
                'max:100',
                'in:' . implode(',', $this->allowedCategories()),
            ],

            // 5MB, common office & image formats
            'file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,webp,bmp,heic,doc,docx,xls,xlsx',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category.in' => 'Please select a valid category.',
            'file.mimes'  => 'Unsupported file type. Please upload a PDF, image, or office document.',
        ];
    }
}
