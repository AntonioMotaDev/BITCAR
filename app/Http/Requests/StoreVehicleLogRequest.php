<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_id' => ['sometimes', 'exists:vehicles,id'],
            'checklist_id' => ['required', 'exists:checklists,id'],
            'type' => ['required', 'in:entry,exit,trip_start,trip_checkpoint,trip_end,fuel,incident,maintenance,other'],
            'mileage' => ['required', 'numeric', 'min:0'],
            'fuel_level' => ['required', 'numeric', 'min:0', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'notes' => ['nullable', 'string'],
            
            // Items pueden venir como JSON string o array
            'items' => ['required'],
            
            // Fotos globales
            'photos' => ['sometimes', 'array'],
            'photos.*' => ['image', 'max:5120'], // 5MB max
            
            // Firma global (deprecada, usar item_signature_X)
            'signature' => ['sometimes', 'string'], // Base64
        ];
    }
    
    /**
     * Preparar datos antes de validación
     */
    protected function prepareForValidation(): void
    {
        // Si items viene como JSON string, parsearlo
        if ($this->has('items') && is_string($this->items)) {
            $this->merge([
                'items' => json_decode($this->items, true),
            ]);
        }
    }
}
