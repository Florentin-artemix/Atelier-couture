@extends('layouts.admin')
@section('page-title', 'Modifier ' . $category->nom)

@section('content')
<a href="{{ route('admin.categories.index') }}" class="text-sm text-cendre hover:text-terracotta-500 mb-6 inline-block">&larr; Retour</a>

<x-ui.card title="Modifier la catégorie">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="space-y-5">
            <x-forms.input name="nom" label="Nom" required :value="$category->nom" />
            <x-forms.textarea name="description" label="Description" rows="2" :value="$category->description" />
            <x-forms.file-upload name="image" label="Image illustrative" />
            <x-forms.input name="ordre_affichage" label="Ordre d'affichage" type="number" :value="$category->ordre_affichage" />
            <x-forms.checkbox name="is_active" label="Catégorie active" :checked="$category->is_active" />
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
    </form>
</x-ui.card>
@endsection
