@extends('layouts.public')

@section('title', 'Portfolio - Atelier Couture')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="font-display text-3xl font-semibold text-charbon">Nos Realisations</h1>
    <p class="mt-2 text-cendre">Decouvrez nos creations realisees pour nos clients.</p>

    {{-- Gallery grid --}}
    <div class="mt-8 columns-1 sm:columns-2 lg:columns-3 gap-4 space-y-4">
        @forelse($realisations as $realisation)
            <div class="break-inside-avoid bg-white rounded-couture shadow-sm border border-lin overflow-hidden">
                @if($realisation->image_url)
                    <img src="{{ $realisation->image_url }}" alt="{{ $realisation->titre }}" class="w-full object-cover">
                @else
                    <div class="aspect-square bg-sable flex items-center justify-center">
                        <svg class="h-16 w-16 text-cendre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
                <div class="p-4">
                    <h3 class="font-display text-lg font-semibold text-charbon">{{ $realisation->titre }}</h3>
                    @if($realisation->description)
                        <p class="mt-1 text-sm text-cendre">{{ $realisation->description }}</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <x-ui.empty-state message="Aucune realisation pour le moment." />
            </div>
        @endforelse
    </div>

    @if(isset($realisations) && $realisations->hasPages())
        <div class="mt-8">
            {{ $realisations->links() }}
        </div>
    @endif
</div>
@endsection
