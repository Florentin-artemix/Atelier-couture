@extends('layouts.admin')
@section('page-title', $modele->nom)

@section('content')
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('admin.catalogue.index') }}" class="text-sm text-cendre hover:text-terracotta-500">&larr; Catalogue</a>
    <a href="{{ route('admin.catalogue.edit', $modele) }}" class="btn-secondary btn-sm">Modifier</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <x-ui.card>
            <div class="aspect-[4/3] bg-sable rounded-couture overflow-hidden mb-6">
                @if($modele->image_principale)
                    <img src="{{ Storage::disk('r2')->url($modele->image_principale) }}" alt="{{ $modele->nom }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-lin">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
            </div>
            <h2 class="font-display text-2xl font-bold text-charbon">{{ $modele->nom }}</h2>
            @if($modele->description)
                <p class="mt-3 text-cendre leading-relaxed">{{ $modele->description }}</p>
            @endif
        </x-ui.card>
    </div>

    <div class="space-y-6">
        <x-ui.card title="Informations">
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-cendre">Catégorie</span><span class="font-medium">{{ $modele->categorie->nom ?? '—' }}</span></div>
                <div class="flex justify-between"><span class="text-cendre">Prix de base</span><span class="font-medium">{{ number_format($modele->prix_base, 0, ',', ' ') }} F</span></div>
                <div class="flex justify-between"><span class="text-cendre">Coefficient</span><span class="font-medium">{{ $modele->coefficient_complexite }}x</span></div>
                <div class="flex justify-between"><span class="text-cendre">Durée estimée</span><span class="font-medium">{{ $modele->duree_estimee_jours }} jours</span></div>
                <div class="flex justify-between"><span class="text-cendre">Statut</span><span class="font-medium">{{ $modele->is_active ? 'Actif' : 'Inactif' }}</span></div>
            </div>
        </x-ui.card>
    </div>
</div>
@endsection
