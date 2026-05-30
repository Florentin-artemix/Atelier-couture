<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => ['sometimes', 'required', 'string', 'max:255'],
            'categorie_modele_id' => ['sometimes', 'required', 'exists:categorie_modeles,id'],
            'prix_base' => ['sometimes', 'numeric', 'min:0'],
            'coefficient_complexite' => ['sometimes', 'numeric', 'min:0.5', 'max:5'],
            'duree_estimee_jours' => ['sometimes', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'image_principale' => ['nullable', 'image', 'max:5120'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
