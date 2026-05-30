@extends('layouts.admin')

@section('page-title', 'Categories')

@section('content')
    <div class="max-w-2xl">
        {{-- Add category form --}}
        <x-ui.card title="Ajouter une categorie">
            <form method="POST" action="{{ route('admin.categories.store') }}" class="flex gap-3">
                @csrf
                <input type="text" name="nom" placeholder="Nom de la categorie" required
                       class="flex-1 px-3 py-2 border border-lin rounded-couture bg-white text-charbon placeholder-cendre focus:outline-none focus:ring-2 focus:ring-terracotta-500 text-sm">
                <button type="submit" class="px-4 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition text-sm">
                    Ajouter
                </button>
            </form>
        </x-ui.card>

        {{-- Categories list --}}
        <div class="mt-6 bg-white rounded-couture shadow-sm border border-lin overflow-hidden">
            <ul class="divide-y divide-lin">
                @forelse($categories as $categorie)
                    <li x-data="{ editing: false }" class="px-4 py-3">
                        {{-- Display mode --}}
                        <div x-show="!editing" class="flex items-center justify-between">
                            <span class="text-charbon">{{ $categorie->nom }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-cendre">{{ $categorie->modeles_count ?? 0 }} modele(s)</span>
                                <button @click="editing = true" class="text-xs text-terracotta-500 hover:text-terracotta-600">Modifier</button>
                            </div>
                        </div>
                        {{-- Edit mode --}}
                        <form x-show="editing" method="POST" action="{{ route('admin.categories.update', $categorie) }}" class="flex gap-2">
                            @csrf
                            @method('PUT')
                            <input type="text" name="nom" value="{{ $categorie->nom }}"
                                   class="flex-1 px-3 py-1 border border-lin rounded-couture text-sm focus:outline-none focus:ring-2 focus:ring-terracotta-500">
                            <button type="submit" class="px-3 py-1 bg-terracotta-500 text-white rounded-couture text-xs">Sauver</button>
                            <button type="button" @click="editing = false" class="px-3 py-1 bg-white border border-lin rounded-couture text-xs text-cendre">Annuler</button>
                        </form>
                    </li>
                @empty
                    <li class="px-4 py-8 text-center text-cendre">Aucune categorie.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
