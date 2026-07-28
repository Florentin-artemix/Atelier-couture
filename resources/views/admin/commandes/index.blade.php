@extends('layouts.admin')

@section('page-title', 'Commandes')

@section('content')
    {{-- Header with filters --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.commandes.index') }}"
               class="px-3 py-1.5 text-sm rounded-couture {{ !request('statut') ? 'bg-terracotta-500 text-white' : 'bg-white text-cendre border border-lin hover:border-terracotta-300' }}">
                Toutes
            </a>
            @foreach(\App\Enums\OrderStatus::cases() as $status)
                <a href="{{ route('admin.commandes.index', ['statut' => $status->value]) }}"
                   class="px-3 py-1.5 text-sm rounded-couture {{ request('statut') === $status->value ? 'bg-terracotta-500 text-white' : 'bg-white text-cendre border border-lin hover:border-terracotta-300' }}">
                    {{ $status->label() }}
                </a>
            @endforeach
        </div>
        <a href="{{ route('admin.commandes.create') }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition text-sm">
            + Nouvelle commande
        </a>
    </div>

    {{-- Orders table --}}
    <div class="bg-white rounded-couture shadow-sm border border-lin overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-sm">
                <thead class="bg-sable">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Reference</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Client</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Modele</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Statut</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-lin">
                    @forelse($commandes as $commande)
                        <tr class="hover:bg-sable/50 transition">
                            <td class="px-4 py-3 font-mono text-charbon">{{ $commande->reference }}</td>
                            <td class="px-4 py-3 text-charbon">{{ $commande->client->nom ?? '-' }}</td>
                            <td class="px-4 py-3 text-cendre">{{ $commande->modele->nom ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <x-ui.badge :color="$commande->statut->color()" :label="$commande->statut->label()" />
                            </td>
                            <td class="px-4 py-3 text-cendre">{{ $commande->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.commandes.show', $commande) }}" class="text-terracotta-500 hover:text-terracotta-600">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-cendre">Aucune commande trouvee.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($commandes->hasPages())
        <div class="mt-6">
            {{ $commandes->links() }}
        </div>
    @endif
@endsection
