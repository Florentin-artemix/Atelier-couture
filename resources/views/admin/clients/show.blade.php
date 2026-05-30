@extends('layouts.admin')

@section('page-title', $client->nom)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.clients.index') }}" class="text-sm text-cendre hover:text-terracotta-500">&larr; Retour aux clients</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Client info --}}
        <div class="lg:col-span-1">
            <x-ui.card title="Informations">
                <dl class="text-sm space-y-3">
                    <div>
                        <dt class="text-cendre">Nom</dt>
                        <dd class="font-medium text-charbon">{{ $client->nom }}</dd>
                    </div>
                    <div>
                        <dt class="text-cendre">Telephone</dt>
                        <dd class="font-medium text-charbon">{{ $client->telephone ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-cendre">Email</dt>
                        <dd class="font-medium text-charbon">{{ $client->email ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-cendre">Adresse</dt>
                        <dd class="font-medium text-charbon">{{ $client->adresse ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-cendre">Client depuis</dt>
                        <dd class="font-medium text-charbon">{{ $client->created_at->format('d/m/Y') }}</dd>
                    </div>
                </dl>
            </x-ui.card>

            {{-- Measurements link --}}
            <div class="mt-4">
                <a href="{{ route('admin.mesures.index', $client) }}" class="block text-center px-4 py-2 bg-white border border-lin rounded-couture text-sm text-cendre hover:bg-sable transition">
                    Voir les mesures
                </a>
            </div>
        </div>

        {{-- Order history --}}
        <div class="lg:col-span-2">
            <x-ui.card title="Historique des commandes">
                @if(isset($commandes) && $commandes->count())
                    <div class="space-y-3">
                        @foreach($commandes as $commande)
                            <x-order.order-card :commande="$commande" />
                        @endforeach
                    </div>
                @else
                    <x-ui.empty-state message="Aucune commande pour ce client." />
                @endif
            </x-ui.card>
        </div>
    </div>
@endsection
