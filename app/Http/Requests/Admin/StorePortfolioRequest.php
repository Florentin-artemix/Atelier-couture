<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePortfolioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image_principale' => ['nullable', 'image', 'max:5120'],
            'date_realisation' => ['nullable', 'date'],
            'is_visible' => ['nullable', 'boolean'],
            'categorie_modele_id' => ['nullable', 'exists:categorie_modeles,id'],
            'modele_id' => ['nullable', 'exists:modeles,id'],
            'commande_id' => ['nullable', 'exists:commandes,id'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_visible' => $this->boolean('is_visible'),
        ]);
    }
}
