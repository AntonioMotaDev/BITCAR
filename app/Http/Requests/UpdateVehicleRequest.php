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
            'code' => ['sometimes', 'string', 'max:255', 'unique:vehicles,code,' . $vehicleId],
            'plate' => ['sometimes', 'string', 'max:255', 'unique:vehicles,plate,' . $vehicleId],
            'brand' => ['sometimes', 'string', 'max:255'],
            'model' => ['sometimes', 'string', 'max:255'],
            'year' => ['sometimes', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'status' => ['sometimes', 'in:active,inactive,maintenance'],
        ];
    }
}
