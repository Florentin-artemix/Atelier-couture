@extends('layouts.admin')

@section('page-title', 'Commande ' . $commande->reference)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.commandes.index') }}" class="text-sm text-cendre hover:text-terracotta-500">&larr; Retour aux commandes</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Status timeline --}}
            <x-ui.card title="Progression">
                <x-order.status-timeline :statut="$commande->statut" />
            </x-ui.card>

            {{-- Order details --}}
            <x-ui.card title="Details de la commande">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-cendre">Reference</dt>
                        <dd class="font-mono font-medium text-charbon">{{ $commande->reference }}</dd>
                    </div>
                    <div>
                        <dt class="text-cendre">Type</dt>
                        <dd class="font-medium text-charbon">{{ $commande->type->label() }}</dd>
                    </div>
                    <div>
                        <dt class="text-cendre">Modele</dt>
                        <dd class="font-medium text-charbon">{{ $commande->modele->nom ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-cendre">Date de creation</dt>
                        <dd class="font-medium text-charbon">{{ $commande->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    @if($commande->date_livraison_prevue)
                        <div>
                            <dt class="text-cendre">Livraison prevue</dt>
                            <dd class="font-medium text-charbon">{{ $commande->date_livraison_prevue->format('d/m/Y') }}</dd>
                        </div>
                    @endif
                    @if($commande->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-cendre">Notes</dt>
                            <dd class="text-charbon">{{ $commande->notes }}</dd>
                        </div>
                    @endif
                </dl>
            </x-ui.card>

            {{-- Accessories --}}
            @if($commande->accessoires && $commande->accessoires->count())
                <x-ui.card title="Accessoires">
                    <ul class="divide-y divide-lin">
                        @foreach($commande->accessoires as $accessoire)
                            <li class="py-2 flex justify-between text-sm">
                                <span class="text-charbon">{{ $accessoire->nom }}</span>
                                <span class="text-cendre">{{ number_format($accessoire->pivot->prix_unitaire ?? $accessoire->prix, 0, ',', ' ') }} FCFA</span>
                            </li>
                        @endforeach
                    </ul>
                </x-ui.card>
            @endif

            {{-- Status change --}}
            @if(!$commande->statut->isTerminal())
                <x-ui.card title="Changer le statut">
                    <form method="POST" action="{{ route('admin.commandes.updateStatus', $commande) }}" class="flex flex-wrap gap-2">
                        @csrf
                        @method('PATCH')
                        @foreach($commande->statut->allowedTransitions() as $transition)
                            <button type="submit" name="statut" value="{{ $transition->value }}"
                                    class="px-3 py-1.5 text-sm rounded-couture border border-lin hover:bg-sable transition text-charbon">
                                {{ $transition->label() }}
                            </button>
                        @endforeach
                    </form>
                </x-ui.card>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Client info --}}
            <x-ui.card title="Client">
                @if($commande->client)
                    <div class="text-sm space-y-2">
                        <p class="font-medium text-charbon">{{ $commande->client->nom }}</p>
                        <p class="text-cendre">{{ $commande->client->telephone ?? '' }}</p>
                        <p class="text-cendre">{{ $commande->client->email ?? '' }}</p>
                        <a href="{{ route('admin.clients.show', $commande->client) }}" class="text-terracotta-500 hover:text-terracotta-600 text-sm">
                            Voir la fiche client
                        </a>
                    </div>
                @else
                    <p class="text-sm text-cendre">Client non associe</p>
                @endif
            </x-ui.card>

            {{-- Pricing --}}
            <x-ui.card title="Tarification">
                <div class="text-sm space-y-2">
                    <div class="flex justify-between">
                        <span class="text-cendre">Prix de base</span>
                        <span class="text-charbon">{{ number_format($commande->prix_base ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @if($commande->prix_final)
                        <div class="flex justify-between font-semibold pt-2 border-t border-lin">
                            <span class="text-charbon">Prix final</span>
                            <span class="text-terracotta-500">{{ number_format($commande->prix_final, 0, ',', ' ') }} FCFA</span>
                        </div>
                    @endif
                </div>

                {{-- Set prix final form --}}
                <form method="POST" action="{{ route('admin.commandes.setPrixFinal', $commande) }}" class="mt-4 pt-4 border-t border-lin">
                    @csrf
                    @method('PATCH')
                    <x-forms.input label="Prix final" name="prix_final" type="number" :value="$commande->prix_final ?? ''" />
                    <button type="submit" class="w-full px-3 py-2 bg-terracotta-500 text-white text-sm rounded-couture hover:bg-terracotta-600 transition">
                        Definir le prix
                    </button>
                </form>
            </x-ui.card>
        </div>
    </div>
@endsection
