<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tenantId = (int) auth()->user()->tenant_id;
        $location = $this->route('location');

        return [
            'name'  => ['required','string','max:150',
                Rule::unique('locations','name')
                    ->ignore($location->id)
                    ->where(fn($q) => $q->where('tenant_id',$tenantId)->whereNull('deleted_at')),
            ],
            'type'  => ['required','in:care_home,supported_living,day_centre,domiciliary'],
            'status'=> ['required','in:active,inactive'],

            'address_line1' => ['nullable','string','max:150'],
            'address_line2' => ['nullable','string','max:150'],
            'city'          => ['nullable','string','max:120'],
            'postcode'      => ['nullable','string','max:20'],
            'country'       => ['nullable','string','max:100'],

            'phone' => ['nullable','string','max:40'],
            'email' => ['nullable','email','max:150'],

            'lat'   => ['nullable','numeric','between:-90,90'],
            'lng'   => ['nullable','numeric','between:-180,180'],
            'geofence_radius_m' => ['nullable','integer','between:10,1000'],
        ];
    }
}
