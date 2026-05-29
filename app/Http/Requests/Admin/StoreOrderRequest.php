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
            'date_livraison_prevue' => ['required', 'date', 'after:today'],
            'accessoires' => ['nullable', 'array'],
            'accessoires.*.accessoire_id' => ['required_with:accessoires', 'exists:accessoires,id'],
            'accessoires.*.quantite' => ['nullable', 'integer', 'min:1'],
            'accessoires.*.fourni_par_client' => ['nullable', 'boolean'],
        ];
    }
}
