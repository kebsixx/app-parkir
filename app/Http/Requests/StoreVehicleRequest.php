<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'plate_number' => strtoupper(preg_replace('/\s+/', ' ', trim((string) $this->plate_number))),
        ]);
    }

    public function rules(): array
    {
        return [
            'vehicle_type_id' => ['required', 'exists:vehicle_types,id'],
            'plate_number' => ['required', 'string', 'max:20', Rule::unique('vehicles', 'plate_number')],
            'owner_name' => ['nullable', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:30'],
        ];
    }
}
