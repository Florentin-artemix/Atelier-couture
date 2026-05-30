@extends('layouts.public')

@section('title', 'Mes commandes - Atelier Couture')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="font-display text-3xl font-semibold text-charbon">Mes commandes</h1>
    <p class="mt-2 text-cendre">Retrouvez l'ensemble de vos commandes et leur avancement.</p>

    <div class="mt-8 space-y-4">
        @forelse($commandes as $commande)
            <a href="{{ route('public.suivi.commande', $commande->lien_suivi) }}" class="block">
                <x-order.order-card :commande="$commande" />
            </a>
        @empty
            <x-ui.empty-state message="Aucune commande trouvee." />
        @endforelse
    </div>
</div>
@endsection
