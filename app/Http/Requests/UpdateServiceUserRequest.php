<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        // Same as store, but all fields nullable (partial updates allowed)
        $store = (new StoreServiceUserRequest())->rules();

        // make required fields optional on update
        $store['first_name'][0]     = 'sometimes';
        $store['last_name'][0]      = 'sometimes';
        $store['date_of_birth'][0]  = 'sometimes';
        $store['address_line1'][0]  = 'sometimes';
        $store['city'][0]           = 'sometimes';
        $store['postcode'][0]       = 'sometimes';
        $store['admission_date'][0] = 'sometimes';

        return $store;
    }
}
