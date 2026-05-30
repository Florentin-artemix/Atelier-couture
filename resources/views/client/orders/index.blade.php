@extends('layouts.client')

@section('title', 'Mes commandes')

@section('content')
    <h1 class="font-display text-2xl font-semibold text-charbon">Mes commandes</h1>
    <p class="mt-1 text-sm text-cendre">Suivez l'avancement de vos commandes.</p>

    <div class="mt-8 space-y-4">
        @forelse($commandes as $commande)
            <a href="{{ route('client.orders.show', $commande) }}" class="block">
                <x-order.order-card :commande="$commande" />
            </a>
        @empty
            <x-ui.empty-state message="Vous n'avez aucune commande pour le moment." />
        @endforelse
    </div>

    @if(isset($commandes) && method_exists($commandes, 'hasPages') && $commandes->hasPages())
        <div class="mt-6">
            {{ $commandes->links() }}
        </div>
    @endif
@endsection
