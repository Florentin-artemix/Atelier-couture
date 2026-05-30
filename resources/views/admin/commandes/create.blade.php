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
                    :required="true"
                />

                <x-forms.select
                    label="Modele"
                    name="modele_id"
                    :options="$modeles->pluck('nom', 'id')->toArray()"
                    :required="true"
                />

                <x-forms.select
                    label="Type de commande"
                    name="type"
                    :options="collect(\App\Enums\OrderType::cases())->mapWithKeys(fn($t) => [$t->value => $t->label()])->toArray()"
                    :required="true"
                />

                <x-forms.input label="Date de livraison prevue" name="date_livraison_prevue" type="date" />

                <x-forms.textarea label="Notes" name="notes" rows="3" />

                {{-- Accessories --}}
                @if(isset($accessoires) && $accessoires->count())
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-charbon mb-2">Accessoires</label>
                        <div class="space-y-2">
                            @foreach($accessoires as $accessoire)
                                <x-forms.checkbox
                                    :label="$accessoire->nom . ' (' . number_format($accessoire->prix_unitaire, 0, ',', ' ') . ' FC/' . $accessoire->unite . ')'"
                                    :name="'accessoires[' . $accessoire->id . ']'"
                                />
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="pt-4 border-t border-lin">
                    <button type="submit" class="px-6 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition font-medium">
                        Creer la commande
                    </button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
