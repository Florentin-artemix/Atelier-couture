@extends('layouts.admin')
@section('page-title', 'Nouvel accessoire')

@section('content')
<a href="{{ route('admin.accessoires.index') }}" class="text-sm text-cendre hover:text-terracotta-500 mb-6 inline-block">&larr; Retour</a>

<x-ui.card title="Créer un accessoire">
    <form action="{{ route('admin.accessoires.store') }}" method="POST">
        @csrf
        <div class="space-y-5">
            <x-forms.input name="nom" label="Nom" required placeholder="Ex: Bouton fantaisie" />
            <x-forms.textarea name="description" label="Description" rows="2" />
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-forms.input name="prix_unitaire" label="Prix unitaire" type="number" step="0.01" required placeholder="0.00" />
                <x-forms.input name="unite" label="Unité" required placeholder="pièce, mètre, lot..." />
            </div>
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.accessoires.index') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Créer</button>
        </div>
    </form>
</x-ui.card>
@endsection
