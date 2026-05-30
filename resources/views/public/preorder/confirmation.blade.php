@extends('layouts.public')

@section('title', 'Precommande confirmee - Atelier Couture')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
    <div class="bg-white rounded-couture shadow-sm border border-lin p-8">
        {{-- Success icon --}}
        <div class="w-16 h-16 mx-auto bg-green-50 rounded-full flex items-center justify-center">
            <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h1 class="mt-6 font-display text-2xl font-semibold text-charbon">Precommande envoyee !</h1>
        <p class="mt-3 text-cendre">
            Votre precommande a bien ete enregistree. Nous vous contacterons prochainement pour la suite.
        </p>

        @if(isset($commande))
            <div class="mt-6 p-4 bg-sable rounded-couture">
                <p class="text-sm text-cendre">Reference de suivi</p>
                <p class="mt-1 font-mono font-semibold text-charbon">{{ $commande->reference }}</p>
            </div>

            @if($commande->lien_suivi)
                <p class="mt-4 text-sm text-cendre">
                    Suivez votre commande :
                    <a href="{{ route('public.suivi.commande', $commande->lien_suivi) }}" class="text-terracotta-500 hover:underline">
                        Lien de suivi
                    </a>
                </p>
            @endif
        @endif

        <div class="mt-8">
            <a href="{{ route('home') }}" class="text-terracotta-500 hover:text-terracotta-600 font-medium">
                Retour a l'accueil
            </a>
        </div>
    </div>
</div>
@endsection
