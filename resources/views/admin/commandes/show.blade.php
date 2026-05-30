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
                                <span class="text-cendre">{{ number_format($accessoire->pivot->prix_unitaire_snapshot, 0, ',', ' ') }} FC</span>
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
                        <span class="text-cendre">Prix de base ({{ $details['coefficient'] }}x)</span>
                        <span class="text-charbon">{{ number_format($details['prix_base_calcule'], 0, ',', ' ') }} FC</span>
                    </div>
                    @if($details['total_accessoires'] > 0)
                        <div class="flex justify-between">
                            <span class="text-cendre">Accessoires</span>
                            <span class="text-charbon">+ {{ number_format($details['total_accessoires'], 0, ',', ' ') }} FC</span>
                        </div>
                    @endif
                    @if($details['reduction'] > 0)
                        <div class="flex justify-between">
                            <span class="text-cendre">Reduction (fourni par client)</span>
                            <span class="text-charbon">- {{ number_format($details['reduction'], 0, ',', ' ') }} FC</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-medium pt-2 border-t border-lin">
                        <span class="text-charbon">Prix propose</span>
                        <span class="text-charbon">{{ number_format($commande->prix_propose ?? $details['prix_propose'], 0, ',', ' ') }} FC</span>
                    </div>
                    @if($commande->prix_final)
                        <div class="flex justify-between font-semibold pt-2 border-t border-lin">
                            <span class="text-charbon">Prix final</span>
                            <span class="text-terracotta-500">{{ number_format($commande->prix_final, 0, ',', ' ') }} FC</span>
                        </div>
                    @endif
                </div>

                {{-- Set prix final form --}}
                <form method="POST" action="{{ route('admin.commandes.setPrixFinal', $commande) }}" class="mt-4 pt-4 border-t border-lin">
                    @csrf
                    @method('PATCH')
                    <x-forms.input label="Prix final (FC)" name="prix_final" type="number" :value="$commande->prix_final ?? $commande->prix_propose" />
                    <button type="submit" class="w-full px-3 py-2 bg-terracotta-500 text-white text-sm rounded-couture hover:bg-terracotta-600 transition">
                        Definir le prix
                    </button>
                </form>
            </x-ui.card>

            {{-- Lien de suivi client --}}
            <x-ui.card title="Lien de suivi">
                <p class="text-xs text-cendre mb-2">Envoyez ce lien au client pour qu'il suive sa commande :</p>
                <div class="bg-sable border border-lin rounded-couture p-2 text-xs font-mono text-charbon break-all select-all">
                    {{ url('/suivi/commande/' . $commande->lien_suivi) }}
                </div>
                <p class="text-xs text-cendre mt-2">Le client peut aussi chercher par telephone sur <a href="{{ url('/suivi') }}" class="text-terracotta-500 underline">/suivi</a></p>
            </x-ui.card>

            {{-- Mesures du client --}}
            @if($commande->client)
                <x-ui.card title="Mesures">
                    <a href="{{ route('admin.mesures.index', $commande->client) }}" class="inline-flex items-center text-sm text-terracotta-500 hover:text-terracotta-700">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Voir / Ajouter les mesures
                    </a>
                    @if($commande->type === \App\Enums\OrderType::Precommande)
                        <p class="mt-2 text-xs text-cendre">
                            Pour une precommande, le client saisit lui-meme ses mesures via son lien de suivi.
                        </p>
                    @endif
                </x-ui.card>

                {{-- Mesures supplementaires a demander (precommande complexe) --}}
                @if($commande->type === \App\Enums\OrderType::Precommande)
                    <x-ui.card title="Mesures supplementaires a demander">
                        <p class="text-xs text-cendre mb-3">
                            Le socle de base est toujours demande. Cochez les mesures additionnelles
                            a demander au client si le modele est complexe.
                        </p>
                        <form method="POST" action="{{ route('admin.commandes.demanderMesures', $commande) }}">
                            @csrf
                            @method('PATCH')
                            <div class="space-y-2 max-h-56 overflow-y-auto">
                                @foreach($mesuresOptionnelles as $type)
                                    <label class="flex items-center text-sm">
                                        <input type="checkbox" name="mesures_demandees[]" value="{{ $type->id }}"
                                               @checked(in_array($type->id, $commande->mesures_demandees ?? []))
                                               class="mr-2 rounded border-lin text-terracotta-500 focus:ring-terracotta-500">
                                        <span class="text-charbon">{{ $type->libelle }}</span>
                                        <span class="text-cendre text-xs ml-1">({{ $type->unite }})</span>
                                    </label>
                                @endforeach
                            </div>
                            <button type="submit" class="mt-3 w-full px-3 py-2 bg-terracotta-500 text-white text-sm rounded-couture hover:bg-terracotta-600 transition">
                                Enregistrer les mesures demandees
                            </button>
                        </form>
                    </x-ui.card>
                @endif
            @endif
        </div>
    </div>
@endsection
