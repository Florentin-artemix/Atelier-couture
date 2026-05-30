@extends('layouts.admin')
@section('page-title', 'Modifier ' . $client->nom)

@section('content')
<a href="{{ route('admin.clients.show', $client) }}" class="text-sm text-cendre hover:text-terracotta-500 mb-6 inline-block">&larr; Retour</a>

<x-ui.card title="Modifier le client">
    <form action="{{ route('admin.clients.update', $client) }}" method="POST">
        @csrf @method('PUT')
        <div class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-forms.input name="nom" label="Nom complet" required :value="$client->nom" />
                <x-forms.input name="telephone" label="Téléphone" required :value="$client->telephone" />
            </div>
            <x-forms.input name="email" label="Email" type="email" :value="$client->email" />
            <x-forms.textarea name="adresse" label="Adresse" rows="2" :value="$client->adresse" />
            <x-forms.textarea name="notes" label="Notes internes" rows="2" :value="$client->notes" />
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.clients.show', $client) }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
    </form>
</x-ui.card>
@endsection
