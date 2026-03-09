<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitChecklistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // La autorización se maneja en el controller/policy
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Datos básicos del vehicle log
            'vehicle_id' => 'required|integer|exists:vehicles,id',
            'type' => 'required|string|in:entrada,salida,trip_start,trip_end,trip_checkpoint,fuel,incident,maintenance',
            'mileage' => 'nullable|numeric|min:0',
            'fuel_level' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
            
            // Items del checklist (respuestas)
            'items' => 'required|array|min:1',
            'items.*.checklist_item_id' => 'required|integer|exists:checklist_items,id',
            'items.*.boolean_answer' => 'nullable|boolean',
            'items.*.text_answer' => 'nullable|string|max:500',
            'items.*.numeric_answer' => 'nullable|numeric',
            
            // Fotos globales del log
            'photos' => 'nullable|array|max:10',
            'photos.*' => 'file|image|mimes:jpg,jpeg,png,webp|max:5120',
            
            // Fotos por item (dinámicas)
            'item_photos_*' => 'nullable|array|max:5',
            'item_photos_*.*' => 'file|image|mimes:jpg,jpeg,png,webp|max:5120',
            
            // Firmas por item (dinámicas)
            'item_signature_*' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'vehicle_id.required' => 'El vehículo es requerido',
            'vehicle_id.exists' => 'El vehículo seleccionado no existe',
            'type.required' => 'El tipo de registro es requerido',
            'type.in' => 'El tipo de registro no es válido',
            'items.required' => 'Debe responder al menos un item del checklist',
            'items.min' => 'Debe responder al menos un item del checklist',
            'items.*.checklist_item_id.required' => 'Falta el ID del item',
            'items.*.checklist_item_id.exists' => 'El item del checklist no existe',
            'photos.*.image' => 'Los archivos deben ser imágenes',
            'photos.*.max' => 'Cada foto no debe superar 5MB',
            'item_photos_*.*.image' => 'Los archivos deben ser imágenes',
            'item_photos_*.*.max' => 'Cada foto no debe superar 5MB',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Si 'items' viene como JSON string, decodificarlo
        if ($this->has('items') && is_string($this->input('items'))) {
            $this->merge([
                'items' => json_decode($this->input('items'), true),
            ]);
        }
    }
}
