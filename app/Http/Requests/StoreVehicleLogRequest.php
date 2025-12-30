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
            'type' => ['required', 'in:entry,exit'],
            'odometer' => ['required', 'integer', 'min:0'],
            'fuel_level' => ['required', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array'],
            'items.*.checklist_item_id' => ['required', 'exists:checklist_items,id'],
            'items.*.value' => ['required'],
            'photos' => ['sometimes', 'array'],
            'photos.*' => ['image', 'max:5120'], // 5MB max
            'signature' => ['sometimes', 'string'], // Base64
        ];
    }
}
