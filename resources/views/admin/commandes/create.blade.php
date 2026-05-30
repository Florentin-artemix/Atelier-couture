@extends('layouts.admin')

@section('page-title', 'Nouvelle commande')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.commandes.index') }}" class="text-sm text-cendre hover:text-terracotta-500">&larr; Retour aux commandes</a>
    </div>

    <div class="max-w-2xl">
        <x-ui.card title="Creer une commande">
            <form method="POST" action="{{ route('admin.commandes.store') }}">
                @csrf

                <x-forms.select
                    label="Client"
                    name="client_id"
                    :options="$clients->pluck('nom', 'id')->toArray()"
                    required
                />

                <x-forms.select
                    label="Modele"
                    name="modele_id"
                    :options="$modeles->pluck('nom', 'id')->toArray()"
                    required
                />

                <x-forms.select
                    label="Type de commande"
                    name="type"
                    :options="['physique' => 'Physique (en atelier)', 'a_distance' => 'A distance', 'precommande' => 'Precommande']"
                    required
                />

                <x-forms.input label="Date de livraison prevue" name="date_livraison_prevue" type="date" required />

                <x-forms.textarea label="Notes internes" name="notes_internes" rows="3" />

                <div class="pt-4 border-t border-lin">
                    <button type="submit" class="px-6 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition font-medium">
                        Creer la commande
                    </button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
