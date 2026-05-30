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

                <x-forms.input label="Nom du modele" name="nom" :value="$modele->nom" :required="true" />

                <x-forms.select
                    label="Categorie"
                    name="categorie_modele_id"
                    :options="$categories->pluck('nom', 'id')->toArray()"
                    :selected="$modele->categorie_modele_id"
                    :required="true"
                />

                <x-forms.textarea label="Description" name="description" :value="$modele->description ?? ''" rows="4" />
                <x-forms.input label="Prix de base (FCFA)" name="prix_base" type="number" :value="$modele->prix_base ?? ''" />
                <x-forms.input label="Delai de confection (jours)" name="delai_confection_jours" type="number" :value="$modele->delai_confection_jours ?? ''" />
                <x-forms.file-upload label="Image principale (remplacer)" name="image_principale" />

                <x-forms.checkbox label="Actif (visible dans le catalogue)" name="actif" :checked="$modele->actif" />

                <div class="pt-4 border-t border-lin flex items-center justify-between">
                    <button type="submit" class="px-6 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition font-medium">
                        Enregistrer
                    </button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
