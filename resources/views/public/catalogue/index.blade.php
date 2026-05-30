@extends('layouts.public')

@section('title', 'Catalogue - Atelier Couture')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="font-display text-3xl font-semibold text-charbon">Notre Catalogue</h1>
    <p class="mt-2 text-cendre">Decouvrez nos modeles et trouvez l'inspiration pour votre prochaine tenue.</p>

    {{-- Category filter --}}
    <div class="mt-8">
        <x-catalogue.category-filter :categories="$categories" :selected="$selectedCategory ?? null" />
    </div>

    {{-- Models grid --}}
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($modeles as $modele)
            <a href="{{ route('public.catalogue.show', $modele) }}" class="block">
                <x-catalogue.model-card :modele="$modele" />
            </a>
        @empty
            <div class="col-span-full">
                <x-ui.empty-state message="Aucun modele disponible pour le moment." />
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($modeles->hasPages())
        <div class="mt-8">
            {{ $modeles->links() }}
        </div>
    @endif
</div>
@endsection
