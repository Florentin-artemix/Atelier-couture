<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeasurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'mesures' => ['required', 'array', 'min:1'],
            'mesures.*.mesure_type_id' => ['required', 'exists:mesure_types,id'],
            'mesures.*.valeur' => ['required', 'numeric', 'min:0'],
            'commande_id' => ['nullable', 'exists:commandes,id'],
        ];
    }
}
