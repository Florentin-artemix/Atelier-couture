@props(['modele'])

<div class="bg-white rounded-couture shadow-sm border border-lin overflow-hidden hover:shadow-md transition group">
    {{-- Image placeholder --}}
    <div class="aspect-[3/4] bg-sable flex items-center justify-center">
        @if($modele->image_principale)
            <img src="{{ $modele->image_principale }}" alt="{{ $modele->nom }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
        @else
            <svg class="h-16 w-16 text-cendre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        @endif
    </div>
    {{-- Content --}}
    <div class="p-4">
        <p class="text-xs text-cendre uppercase tracking-wide">{{ $modele->categorie->nom ?? '' }}</p>
        <h3 class="mt-1 font-display text-lg font-semibold text-charbon">{{ $modele->nom }}</h3>
        @if($modele->prix_base)
            <p class="mt-1 text-terracotta-500 font-medium">{{ number_format($modele->prix_base, 0, ',', ' ') }} FC</p>
        @endif
        @if($modele->duree_estimee_jours)
            <p class="mt-1 text-xs text-cendre">~{{ $modele->duree_estimee_jours }} jours</p>
        @endif
    </div>
</div>
