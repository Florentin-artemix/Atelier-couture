@extends('layouts.admin')

@section('page-title', 'Catalogue')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-cendre">{{ $modeles->total() ?? $modeles->count() }} modele(s)</p>
        <a href="{{ route('admin.catalogue.create') }}" class="inline-flex items-center px-4 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition text-sm">
            + Nouveau modele
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($modeles as $modele)
            <div class="relative group">
                <x-catalogue.model-card :modele="$modele" />
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition">
                    <a href="{{ route('admin.catalogue.edit', $modele) }}" class="inline-flex items-center px-2 py-1 bg-white border border-lin rounded text-xs text-cendre hover:text-terracotta-500 shadow-sm">
                        Modifier
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <x-ui.empty-state message="Aucun modele dans le catalogue." />
            </div>
        @endforelse
    </div>

    @if(method_exists($modeles, 'hasPages') && $modeles->hasPages())
        <div class="mt-6">
            {{ $modeles->links() }}
        </div>
    @endif
@endsection
