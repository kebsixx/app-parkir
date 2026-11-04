<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('vehicle_types', 'name')->ignore($this->route('vehicle_type')),
            ],
            'hourly_rate' => ['required', 'integer', 'min:0'],
        ];
    }
}
