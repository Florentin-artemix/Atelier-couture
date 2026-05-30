@extends('layouts.admin')
@section('page-title', 'Nouvelle réalisation')

@section('content')
<a href="{{ route('admin.portfolio.index') }}" class="text-sm text-cendre hover:text-terracotta-500 mb-6 inline-block">&larr; Retour</a>

<x-ui.card title="Ajouter au portfolio">
    <form action="{{ route('admin.portfolio.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-5">
            <x-forms.input name="titre" label="Titre" required placeholder="Ex: Robe de mariée brodée" />
            <x-forms.textarea name="description" label="Description" rows="3" />
            <x-forms.file-upload name="image_principale" label="Photo principale" required />
            <x-forms.input name="date_realisation" label="Date de réalisation" type="date" />
            <x-forms.checkbox name="is_visible" label="Visible sur le site" checked />
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.portfolio.index') }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Ajouter</button>
        </div>
    </form>
</x-ui.card>
@endsection
