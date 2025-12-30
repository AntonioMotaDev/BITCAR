<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChecklistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'version' => ['sometimes', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'items' => ['sometimes', 'array', 'min:1'],
            'items.*.label' => ['required_with:items', 'string', 'max:255'],
            'items.*.type' => ['required_with:items', 'in:boolean,text,number,photo'],
            'items.*.required' => ['sometimes', 'boolean'],
            'items.*.order' => ['sometimes', 'integer'],
        ];
    }
}
