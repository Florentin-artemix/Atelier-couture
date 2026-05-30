@extends('layouts.admin')
@section('page-title', 'Nouvelle catégorie')

@section('content')
<a href="{{ route('admin.categories.index') }}" class="text-sm text-cendre hover:text-terracotta-500 mb-6 inline-block">&larr; Retour</a>

<x-ui.card title="Créer une catégorie">
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-5">
            <x-forms.input name="nom" label="Nom" required placeholder="Ex: Robe" />
            <x-forms.textarea name="description" label="Description" rows="2" />
            <x-forms.file-upload name="image" label="Image illustrative" />
            <x-forms.input name="ordre_affichage" label="Ordre d'affichage" type="number" value="0" />
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Créer</button>
        </div>
    </form>
</x-ui.card>
@endsection
