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
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'max:2048'],
            'modele_id' => ['nullable', 'exists:modeles,id'],
            'commande_id' => ['nullable', 'exists:commandes,id'],
        ];
    }
}
