<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vehicleId = $this->route('vehicle');
        
        return [
            'brand' => ['sometimes', 'string', 'max:100'],
            'model' => ['sometimes', 'string', 'max:100'],
            'year' => ['sometimes', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'license_plate' => ['sometimes', 'string', 'max:20', 'unique:vehicles,license_plate,' . $vehicleId],
            'vin' => ['nullable', 'string', 'max:50', 'unique:vehicles,vin,' . $vehicleId],
            'color' => ['sometimes', 'string', 'max:50'],
            'type' => ['sometimes', 'in:pickup,sedan,suv,van,camion'],
            'mileage' => ['sometimes', 'numeric', 'min:0'],
            'fuel_capacity' => ['sometimes', 'numeric', 'min:0'],
            'status' => ['sometimes', 'in:activo,mantenimiento,inactivo'],
        ];
    }
}
