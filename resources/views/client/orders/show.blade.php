@extends('layouts.client')

@section('title', 'Commande ' . $commande->reference)

@section('content')
    <div class="mb-6">
        <a href="{{ route('client.orders.index') }}" class="text-sm text-cendre hover:text-terracotta-500">&larr; Retour a mes commandes</a>
    </div>

    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-2xl font-semibold text-charbon">Commande {{ $commande->reference }}</h1>
                <p class="text-sm text-cendre">Creee le {{ $commande->created_at->format('d/m/Y') }}</p>
            </div>
            <x-ui.badge :color="$commande->statut->color()" :label="$commande->statut->label()" />
        </div>

        {{-- Status timeline --}}
        <x-ui.card title="Progression">
            <x-order.status-timeline :statut="$commande->statut" />
        </x-ui.card>

        {{-- Order details --}}
        <x-ui.card title="Details">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-cendre">Modele</dt>
                    <dd class="font-medium text-charbon">{{ $commande->modele->nom ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-cendre">Type</dt>
                    <dd class="font-medium text-charbon">{{ $commande->type->label() }}</dd>
                </div>
                @if($commande->date_livraison_prevue)
                    <div>
                        <dt class="text-cendre">Livraison prevue</dt>
                        <dd class="font-medium text-charbon">{{ $commande->date_livraison_prevue->format('d/m/Y') }}</dd>
                    </div>
                @endif
                @if($commande->prix_final)
                    <div>
                        <dt class="text-cendre">Prix</dt>
                        <dd class="font-medium text-terracotta-500">{{ number_format($commande->prix_final, 0, ',', ' ') }} FC</dd>
                    </div>
                @endif
            </dl>
        </x-ui.card>

        {{-- Accessories --}}
        @if($commande->accessoires && $commande->accessoires->count())
            <x-ui.card title="Accessoires">
                <ul class="divide-y divide-lin text-sm">
                    @foreach($commande->accessoires as $accessoire)
                        <li class="py-2 flex justify-between">
                            <span class="text-charbon">{{ $accessoire->nom }}</span>
                            <span class="text-cendre">{{ number_format($accessoire->pivot->prix_unitaire ?? $accessoire->prix, 0, ',', ' ') }} FC</span>
                        </li>
                    @endforeach
                </ul>
            </x-ui.card>
        @endif
    </div>
@endsection
