<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_livraison_prevue' => ['sometimes', 'date', 'after:today'],
            'notes_internes' => ['nullable', 'string'],
            'notes_client' => ['nullable', 'string'],
            'prix_final' => ['nullable', 'numeric', 'min:0'],
            'accessoires' => ['nullable', 'array'],
            'accessoires.*.accessoire_id' => ['required_with:accessoires', 'exists:accessoires,id'],
            'accessoires.*.quantite' => ['nullable', 'integer', 'min:1'],
            'accessoires.*.fourni_par_client' => ['nullable', 'boolean'],
        ];
    }
}
