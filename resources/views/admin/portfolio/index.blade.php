@extends('layouts.admin')

@section('page-title', 'Portfolio')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-cendre">{{ $realisations->count() }} realisation(s)</p>
        <a href="{{ route('admin.portfolio.create') }}" class="inline-flex items-center px-4 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition text-sm">
            + Nouvelle realisation
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($realisations as $realisation)
            <div class="bg-white rounded-couture shadow-sm border border-lin overflow-hidden group relative">
                <div class="aspect-square bg-sable flex items-center justify-center overflow-hidden">
                    @if($realisation->image_url)
                        <img src="{{ $realisation->image_url }}" alt="{{ $realisation->titre }}" class="w-full h-full object-cover">
                    @else
                        <svg class="h-12 w-12 text-cendre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-charbon">{{ $realisation->titre }}</h3>
                    @if($realisation->description)
                        <p class="mt-1 text-sm text-cendre line-clamp-2">{{ $realisation->description }}</p>
                    @endif
                </div>
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition flex space-x-1">
                    <a href="{{ route('admin.portfolio.edit', $realisation) }}" class="px-2 py-1 bg-white text-charbon rounded text-xs shadow-sm border border-lin hover:bg-sable">Modifier</a>
                    <form method="POST" action="{{ route('admin.portfolio.destroy', $realisation) }}" onsubmit="return confirm('Supprimer cette realisation ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded text-xs shadow-sm">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <x-ui.empty-state message="Aucune realisation dans le portfolio." />
            </div>
        @endforelse
    </div>
@endsection
