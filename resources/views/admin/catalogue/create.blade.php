@extends('layouts.admin')

@section('page-title', 'Nouveau modele')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.catalogue.index') }}" class="text-sm text-cendre hover:text-terracotta-500">&larr; Retour au catalogue</a>
    </div>

    <div class="max-w-2xl">
        <x-ui.card title="Creer un modele">
            <form method="POST" action="{{ route('admin.catalogue.store') }}" enctype="multipart/form-data">
                @csrf

                <x-forms.input label="Nom du modele" name="nom" :required="true" />

                <x-forms.select
                    label="Categorie"
                    name="categorie_modele_id"
                    :options="$categories->pluck('nom', 'id')->toArray()"
                    :required="true"
                />

                <x-forms.textarea label="Description" name="description" rows="4" />
                <x-forms.input label="Prix de base (FCFA)" name="prix_base" type="number" />
                <x-forms.input label="Delai de confection (jours)" name="delai_confection_jours" type="number" />
                <x-forms.file-upload label="Image principale" name="image_principale" />

                <x-forms.checkbox label="Actif (visible dans le catalogue)" name="actif" :checked="true" />

                <div class="pt-4 border-t border-lin">
                    <button type="submit" class="px-6 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition font-medium">
                        Creer le modele
                    </button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
