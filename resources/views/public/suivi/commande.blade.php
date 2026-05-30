@extends('layouts.public')

@section('title', 'Suivi de commande - Atelier Couture')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="font-display text-3xl font-semibold text-charbon">Suivi de commande</h1>

    @if(session('success'))
        <div class="mt-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-couture text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mt-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-couture text-sm">
            {{ session('error') }}
        </div>
    @endif

    <x-ui.card class="mt-8">
        {{-- Reference --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-sm text-cendre">Reference</p>
                <p class="font-mono font-semibold text-charbon">{{ $commande->reference }}</p>
            </div>
            <x-ui.badge :color="$commande->statut->color()" :label="$commande->statut->label()" />
        </div>

        {{-- Status timeline --}}
        <div class="py-6 border-t border-b border-lin">
            <x-order.status-timeline :statut="$commande->statut" />
        </div>

        {{-- Details --}}
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-cendre">Modele</p>
                <p class="font-medium text-charbon">{{ $commande->modele->nom ?? '-' }}</p>
            </div>
            <div>
                <p class="text-cendre">Date de commande</p>
                <p class="font-medium text-charbon">{{ $commande->created_at->format('d/m/Y') }}</p>
            </div>
            @if($commande->date_livraison_prevue)
                <div>
                    <p class="text-cendre">Livraison prevue</p>
                    <p class="font-medium text-charbon">{{ $commande->date_livraison_prevue->format('d/m/Y') }}</p>
                </div>
            @endif
            @if($commande->prix_final || $commande->prix_propose)
                <div>
                    <p class="text-cendre">Prix</p>
                    <p class="font-medium text-charbon">{{ number_format($commande->prix_final ?? $commande->prix_propose, 0, ',', ' ') }} FC</p>
                </div>
            @endif
        </div>
    </x-ui.card>

    {{-- Formulaire de saisie des mesures (si la commande attend les mesures) --}}
    @if($commande->statut === \App\Enums\OrderStatus::EnAttenteMesures && $typesMesures->count())
        <x-ui.card class="mt-8">
            <h2 class="font-display text-xl font-semibold text-charbon">Renseignez vos mesures</h2>
            <p class="mt-1 text-sm text-cendre">
                Le couturier a besoin de vos mesures pour lancer la confection.
                Les champs marques d'un <span class="text-terracotta-500">*</span> sont obligatoires.
            </p>

            <form method="POST" action="{{ route('public.suivi.mesures', $commande->lien_suivi) }}" class="mt-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($typesMesures as $type)
                        <div>
                            <label class="block text-sm font-medium text-charbon mb-1">
                                {{ $type->libelle }}
                                @if($type->is_base)<span class="text-terracotta-500">*</span>@endif
                                <span class="text-cendre text-xs">({{ $type->unite }})</span>
                            </label>
                            <input
                                type="number" step="0.1" min="0"
                                name="mesures[{{ $type->id }}]"
                                value="{{ old('mesures.' . $type->id, $mesuresExistantes[$type->id]->valeur ?? '') }}"
                                @if($type->is_base) required @endif
                                class="w-full px-3 py-2 border border-lin rounded-couture bg-white text-charbon focus:outline-none focus:ring-2 focus:ring-terracotta-500 text-sm"
                                placeholder="{{ $type->libelle }}"
                            >
                        </div>
                    @endforeach
                </div>

                <label class="flex items-start mt-6 text-sm text-cendre">
                    <input type="checkbox" name="consentement" value="1" required class="mt-0.5 mr-2 rounded border-lin text-terracotta-500 focus:ring-terracotta-500">
                    <span>J'accepte que mes mesures soient collectees et utilisees par l'atelier pour la confection de ma commande.</span>
                </label>

                <button type="submit" class="mt-6 px-6 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition font-medium">
                    Envoyer mes mesures
                </button>
            </form>
        </x-ui.card>
    @endif
</div>
@endsection
