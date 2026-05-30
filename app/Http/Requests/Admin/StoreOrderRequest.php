<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'modele_id' => ['required', 'exists:modeles,id'],
            'type' => ['required', 'in:physique,a_distance,precommande'],
            'date_livraison_prevue' => ['required', 'date', 'after_or_equal:today'],
            'notes_internes' => ['nullable', 'string'],
            'notes_client' => ['nullable', 'string'],
            'accessoires' => ['nullable', 'array'],
        ];
    }
}
