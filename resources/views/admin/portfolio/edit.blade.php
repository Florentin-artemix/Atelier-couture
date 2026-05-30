@extends('layouts.admin')
@section('page-title', 'Modifier — ' . $portfolio->titre)

@section('content')
<a href="{{ route('admin.portfolio.index') }}" class="text-sm text-cendre hover:text-terracotta-500 mb-6 inline-block">&larr; Retour</a>

<x-ui.card title="Modifier la réalisation">
    <form action="{{ route('admin.portfolio.update', $portfolio) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="space-y-5">
            <x-forms.input name="titre" label="Titre" required :value="$portfolio->titre" />
            <x-forms.textarea name="description" label="Description" rows="3" :value="$portfolio->description" />
            <x-forms.file-upload name="image_principale" label="Photo principale (laisser vide pour garder l'actuelle)" />
            <x-forms.input name="date_realisation" label="Date de réalisation" type="date" :value="$portfolio->date_realisation?->format('Y-m-d')" />
            <x-forms.checkbox name="is_visible" label="Visible sur le site" :checked="$portfolio->is_visible" />
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.portfolio.index') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
    </form>
</x-ui.card>
@endsection
