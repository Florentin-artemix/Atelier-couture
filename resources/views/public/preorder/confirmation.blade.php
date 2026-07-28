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
                <div class="mt-4 text-left" x-data="{ copie: false, lien: '{{ route('public.suivi.commande', $commande->lien_suivi) }}' }">
                    <p class="text-sm text-cendre mb-1">Votre lien de suivi (conservez-le precieusement) :</p>
                    <div class="flex flex-col sm:flex-row sm:items-stretch gap-2">
                        <input type="text" readonly :value="lien"
                               class="flex-1 px-3 py-2 border border-lin rounded-couture bg-white text-charbon text-sm font-mono"
                               onclick="this.select()">
                        <button type="button"
                                @click="navigator.clipboard.writeText(lien); copie = true; setTimeout(() => copie = false, 2000)"
                                class="px-4 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition text-sm whitespace-nowrap">
                            <span x-show="!copie">Copier</span>
                            <span x-show="copie" x-cloak>Copie !</span>
                        </button>
                    </div>
                    <a :href="lien" class="mt-3 inline-block text-terracotta-500 hover:underline text-sm">
                        Ouvrir le suivi de ma commande &rarr;
                    </a>
                </div>
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
