<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => ['required', 'string', 'max:255'],
            'commande_id' => ['nullable', 'exists:commandes,id'],
            'client_id' => ['nullable', 'exists:clients,id'],
            'date_echeance' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }
}
