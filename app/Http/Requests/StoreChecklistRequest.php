<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChecklistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'version' => ['sometimes', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.label' => ['required', 'string', 'max:255'],
            'items.*.type' => ['required', 'in:boolean,text,number,photo'],
            'items.*.required' => ['sometimes', 'boolean'],
            'items.*.order' => ['sometimes', 'integer'],
        ];
    }
}
