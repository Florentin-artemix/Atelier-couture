@extends('layouts.admin')

@section('page-title', 'Tableau de bord')

@section('content')
    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-ui.stat-card
            title="Commandes en cours"
            :value="$compteurs['commandes_en_cours']"
            color="blue"
        />
        <x-ui.stat-card
            title="Commandes en retard"
            :value="$compteurs['commandes_en_retard']"
            color="red"
        />
        <x-ui.stat-card
            title="Commandes pretes"
            :value="$compteurs['commandes_pretes']"
            color="green"
        />
        <x-ui.stat-card
            title="CA du mois"
            :value="number_format($compteurs['chiffre_affaires_mois'], 0, ',', ' ') . ' FC'"
            color="terracotta"
        />
    </div>

    {{-- Two-column layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Commandes en retard --}}
        <x-ui.card title="Commandes en retard">
            @forelse($commandes_en_retard as $commande)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-lin' : '' }}">
                    <div>
                        <p class="font-medium text-charbon">{{ $commande->reference }}</p>
                        <p class="text-sm text-cendre">{{ $commande->client->nom ?? '-' }}</p>
                    </div>
                    <x-ui.badge color="red" :label="$commande->statut->label()" />
                </div>
            @empty
                <p class="text-sm text-cendre">Aucune commande en retard.</p>
            @endforelse
        </x-ui.card>

        {{-- Rappels urgents --}}
        <x-ui.card title="Rappels urgents">
            @forelse($rappels_urgents as $rappel)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-lin' : '' }}">
                    <div>
                        <p class="font-medium text-charbon">{{ $rappel->titre }}</p>
                        <p class="text-sm text-cendre">{{ $rappel->date_rappel->format('d/m/Y') }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.rappels.markDone', $rappel) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-xs text-terracotta-500 hover:text-terracotta-600">Fait</button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-cendre">Aucun rappel urgent.</p>
            @endforelse
        </x-ui.card>

        {{-- Precommandes en attente --}}
        <x-ui.card title="Precommandes en attente">
            @forelse($precommandes_en_attente as $commande)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-lin' : '' }}">
                    <div>
                        <p class="font-medium text-charbon">{{ $commande->reference }}</p>
                        <p class="text-sm text-cendre">{{ $commande->client->nom ?? '-' }}</p>
                    </div>
                    <a href="{{ route('admin.commandes.show', $commande) }}" class="text-xs text-terracotta-500 hover:text-terracotta-600">Voir</a>
                </div>
            @empty
                <p class="text-sm text-cendre">Aucune precommande en attente.</p>
            @endforelse
        </x-ui.card>

        {{-- Commandes recentes --}}
        <x-ui.card title="Commandes recentes">
            @forelse($commandes_recentes as $commande)
                <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-lin' : '' }}">
                    <div>
                        <p class="font-medium text-charbon">{{ $commande->reference }}</p>
                        <p class="text-sm text-cendre">{{ $commande->client->nom ?? '-' }} - {{ $commande->created_at->format('d/m/Y') }}</p>
                    </div>
                    <x-ui.badge :color="$commande->statut->color()" :label="$commande->statut->label()" />
                </div>
            @empty
                <p class="text-sm text-cendre">Aucune commande recente.</p>
            @endforelse
        </x-ui.card>
    </div>
@endsection
