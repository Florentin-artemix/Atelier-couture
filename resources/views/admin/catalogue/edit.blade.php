@extends('layouts.admin')

@section('page-title', 'Modifier - ' . $modele->nom)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.catalogue.index') }}" class="text-sm text-cendre hover:text-terracotta-500">&larr; Retour au catalogue</a>
    </div>

    <div class="max-w-2xl">
        <x-ui.card title="Modifier le modele">
            <form method="POST" action="{{ route('admin.catalogue.update', $modele) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <x-forms.input label="Nom du modele" name="nom" :value="$modele->nom" required />

                <x-forms.select
                    label="Categorie"
                    name="categorie_modele_id"
                    :options="$categories->pluck('nom', 'id')->toArray()"
                    :selected="$modele->categorie_modele_id"
                    required
                />

                <x-forms.textarea label="Description" name="description" :value="$modele->description ?? ''" rows="4" />

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <x-forms.input label="Prix de base (FC)" name="prix_base" type="number" :value="$modele->prix_base" required />
                    <x-forms.input label="Coefficient complexite" name="coefficient_complexite" type="number" step="0.01" min="0.5" max="5" :value="$modele->coefficient_complexite" />
                    <x-forms.input label="Duree estimee (jours)" name="duree_estimee_jours" type="number" min="1" :value="$modele->duree_estimee_jours" />
                </div>

                <x-forms.file-upload label="Image principale" name="image_principale" :current="$modele->image_principale ? Storage::disk('r2')->url($modele->image_principale) : null" />

                <x-forms.checkbox label="Actif (visible au catalogue)" name="is_active" :checked="$modele->is_active" />

                <div class="pt-4 border-t border-lin flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.catalogue.index') }}" class="btn-secondary">Annuler</a>
                    <button type="submit" class="btn-primary">Enregistrer</button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
