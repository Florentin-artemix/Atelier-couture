@extends('layouts.admin')
@section('page-title', 'Modifier ' . $accessoire->nom)

@section('content')
<a href="{{ route('admin.accessoires.index') }}" class="text-sm text-cendre hover:text-terracotta-500 mb-6 inline-block">&larr; Retour</a>

<x-ui.card title="Modifier l'accessoire">
    <form action="{{ route('admin.accessoires.update', $accessoire) }}" method="POST">
        @csrf @method('PUT')
        <div class="space-y-5">
            <x-forms.input name="nom" label="Nom" required :value="$accessoire->nom" />
            <x-forms.textarea name="description" label="Description" rows="2" :value="$accessoire->description" />
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-forms.input name="prix_unitaire" label="Prix unitaire" type="number" step="0.01" required :value="$accessoire->prix_unitaire" />
                <x-forms.input name="unite" label="Unité" required :value="$accessoire->unite" />
            </div>
            <x-forms.checkbox name="is_active" label="Actif" :checked="$accessoire->is_active" />
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.accessoires.index') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
    </form>
</x-ui.card>
@endsection
