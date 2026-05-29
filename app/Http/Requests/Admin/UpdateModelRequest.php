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
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
