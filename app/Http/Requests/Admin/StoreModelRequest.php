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
            'coefficient_complexite' => ['nullable', 'numeric', 'min:0.5', 'max:5'],
            'duree_estimee_jours' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'image_principale' => ['nullable', 'image', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
