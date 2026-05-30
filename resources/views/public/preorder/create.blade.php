@extends('layouts.public')

@section('title', 'Precommander - ' . $modele->nom)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="font-display text-3xl font-semibold text-charbon">Precommander</h1>
    <p class="mt-2 text-cendre">Remplissez le formulaire pour precommander <strong>{{ $modele->nom }}</strong>.</p>

    {{-- Model info card --}}
    <div class="mt-6 bg-white rounded-couture border border-lin p-4 flex items-center space-x-4">
        <div class="w-20 h-20 bg-sable rounded-couture flex-shrink-0 flex items-center justify-center overflow-hidden">
            @if($modele->image_principale)
                <img src="{{ $modele->image_principale }}" alt="{{ $modele->nom }}" class="w-full h-full object-cover">
            @else
                <svg class="h-8 w-8 text-cendre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            @endif
        </div>
        <div>
            <p class="font-display font-semibold text-charbon">{{ $modele->nom }}</p>
            <p class="text-sm text-cendre">{{ $modele->categorie->nom ?? '' }}</p>
            @if($modele->prix_base)
                <p class="text-sm text-terracotta-500 font-medium">A partir de {{ number_format($modele->prix_base, 0, ',', ' ') }} FCFA</p>
            @endif
        </div>
    </div>

    {{-- Preorder form --}}
    <form action="{{ route('public.preorder.store') }}" method="POST" class="mt-8 space-y-1">
        @csrf
        <input type="hidden" name="modele_id" value="{{ $modele->id }}">

        <x-forms.input label="Nom complet" name="nom" :required="true" />
        <x-forms.input label="Telephone" name="telephone" type="tel" :required="true" />
        <x-forms.input label="Email" name="email" type="email" />
        <x-forms.textarea label="Notes ou demandes speciales" name="notes" rows="4" />

        <div class="pt-4">
            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition font-medium">
                Envoyer la precommande
            </button>
        </div>
    </form>
</div>
@endsection
