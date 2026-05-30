@extends('layouts.public')

@section('title', $modele->nom . ' - Atelier Couture')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Breadcrumb --}}
    <nav class="mb-6 text-sm text-cendre">
        <a href="{{ route('public.catalogue.index') }}" class="hover:text-terracotta-500">Catalogue</a>
        <span class="mx-2">/</span>
        <span class="text-charbon">{{ $modele->nom }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        {{-- Image --}}
        <div class="aspect-[3/4] bg-sable rounded-couture flex items-center justify-center overflow-hidden">
            @if($modele->image_principale)
                <img src="{{ $modele->image_principale }}" alt="{{ $modele->nom }}" class="w-full h-full object-cover">
            @else
                <svg class="h-24 w-24 text-cendre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            @endif
        </div>

        {{-- Details --}}
        <div>
            <p class="text-sm text-cendre uppercase tracking-wide">{{ $modele->categorie->nom ?? '' }}</p>
            <h1 class="mt-2 font-display text-3xl font-semibold text-charbon">{{ $modele->nom }}</h1>

            @if($modele->prix_base)
                <p class="mt-4 text-2xl text-terracotta-500 font-semibold">
                    A partir de {{ number_format($modele->prix_base, 0, ',', ' ') }} FC
                </p>
            @endif

            @if($modele->description)
                <div class="mt-6 text-cendre leading-relaxed">
                    {!! nl2br(e($modele->description)) !!}
                </div>
            @endif

            @if($modele->duree_estimee_jours)
                <p class="mt-4 text-sm text-cendre">
                    Delai de confection: <span class="font-medium text-charbon">{{ $modele->duree_estimee_jours }} jours</span>
                </p>
            @endif

            <div class="mt-8">
                <a href="{{ route('public.preorder.create', $modele) }}" class="inline-flex items-center px-6 py-3 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition font-medium">
                    Precommander ce modele
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
