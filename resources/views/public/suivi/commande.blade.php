@extends('layouts.public')

@section('title', 'Suivi de commande - Atelier Couture')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="font-display text-3xl font-semibold text-charbon">Suivi de commande</h1>

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
            @if($commande->prix_final)
                <div>
                    <p class="text-cendre">Prix</p>
                    <p class="font-medium text-charbon">{{ number_format($commande->prix_final, 0, ',', ' ') }} FC</p>
                </div>
            @endif
        </div>
    </x-ui.card>
</div>
@endsection
