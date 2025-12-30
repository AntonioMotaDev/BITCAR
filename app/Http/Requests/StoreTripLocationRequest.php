<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTripLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'locations' => ['required', 'array', 'min:1'],
            'locations.*.latitude' => ['required', 'numeric', 'between:-90,90'],
            'locations.*.longitude' => ['required', 'numeric', 'between:-180,180'],
            'locations.*.accuracy' => ['required', 'numeric', 'min:0'],
            'locations.*.speed' => ['nullable', 'numeric', 'min:0'],
            'locations.*.recorded_at' => ['required', 'date'],
        ];
    }
}
