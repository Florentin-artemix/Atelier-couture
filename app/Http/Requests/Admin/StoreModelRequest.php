<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'categorie_modele_id' => ['required', 'exists:categorie_modeles,id'],
            'prix_base' => ['required', 'numeric', 'min:0'],
            'coefficient_complexite' => ['required', 'numeric', 'min:0.5', 'max:5'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
