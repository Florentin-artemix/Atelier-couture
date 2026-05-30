@props(['commande'])

<div class="bg-white rounded-couture shadow-sm border border-lin p-4 hover:shadow-md transition">
    <div class="flex items-center justify-between">
        <div>
            <p class="font-medium text-charbon">{{ $commande->reference }}</p>
            <p class="text-sm text-cendre">{{ $commande->client->nom ?? '-' }}</p>
        </div>
        <x-ui.badge :color="$commande->statut->color()" :label="$commande->statut->label()" />
    </div>
    <div class="mt-3 flex items-center justify-between text-xs text-cendre">
        <span>Creee le {{ $commande->created_at->format('d/m/Y') }}</span>
        @if($commande->date_livraison_prevue)
            <span>Livraison: {{ $commande->date_livraison_prevue->format('d/m/Y') }}</span>
        @endif
    </div>
</div>
