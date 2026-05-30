@extends('layouts.public')

@section('title', 'Suivi de commande - Atelier Couture')

@section('content')
<div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="font-display text-3xl font-semibold text-charbon text-center">Suivi de commande</h1>
    <p class="mt-2 text-cendre text-center">Retrouvez vos commandes en entrant votre lien de suivi ou votre numero de telephone.</p>

    @if(session('error'))
        <div class="mt-6 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-couture text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Lien de suivi direct --}}
    <div class="mt-8">
        <x-ui.card title="J'ai un lien de suivi">
            <form method="GET" action="" id="lien-suivi-form" class="space-y-4">
                <x-forms.input label="Collez votre lien de suivi" name="lien_suivi" placeholder="Ex: https://..." />
                <div class="pt-2">
                    <button type="submit" class="w-full px-4 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition text-sm font-medium">
                        Voir ma commande
                    </button>
                </div>
            </form>
            <script>
                document.getElementById('lien-suivi-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    var lien = this.querySelector('[name="lien_suivi"]').value.trim();
                    if (lien.includes('/suivi/commande/')) {
                        window.location.href = lien;
                    } else if (lien.includes('/suivi/client/')) {
                        window.location.href = lien;
                    } else if (lien.length > 0) {
                        window.location.href = '/suivi/commande/' + lien;
                    }
                });
            </script>
        </x-ui.card>
    </div>

    {{-- Recherche par telephone --}}
    <div class="mt-6">
        <x-ui.card title="Recherche par telephone">
            <form method="POST" action="{{ route('public.suivi.recherche') }}" class="space-y-4">
                @csrf
                <x-forms.input label="Votre numero de telephone" name="telephone" type="tel" placeholder="Ex: +221 77 123 45 67" :required="true" />
                <div class="pt-2">
                    <button type="submit" class="w-full px-4 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition text-sm font-medium">
                        Retrouver mes commandes
                    </button>
                </div>
            </form>
        </x-ui.card>
    </div>
</div>
@endsection
