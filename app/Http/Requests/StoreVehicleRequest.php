<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'license_plate' => ['required', 'string', 'max:20', 'unique:vehicles,license_plate'],
            'vin' => ['nullable', 'string', 'max:50', 'unique:vehicles,vin'],
            'color' => ['required', 'string', 'max:50'],
            'type' => ['required', 'in:pickup,sedan,suv,van,camion'],
            'mileage' => ['required', 'numeric', 'min:0'],
            'fuel_capacity' => ['required', 'numeric', 'min:0'],
            'status' => ['sometimes', 'in:activo,mantenimiento,inactivo'],
        ];
    }
}
