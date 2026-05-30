@extends('layouts.admin')

@section('page-title', 'Rappels')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-cendre">Rappels en attente</p>
        <button @click="$dispatch('open-modal-add-rappel')" class="inline-flex items-center px-4 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition text-sm">
            + Nouveau rappel
        </button>
    </div>

    <div class="space-y-3">
        @forelse($rappels as $rappel)
            <div class="bg-white rounded-couture shadow-sm border border-lin p-4 flex items-center justify-between">
                <div>
                    <p class="font-medium text-charbon">{{ $rappel->titre }}</p>
                    <p class="text-sm text-cendre">
                        {{ $rappel->date_rappel->format('d/m/Y') }}
                        @if($rappel->commande)
                            - Commande {{ $rappel->commande->reference }}
                        @endif
                    </p>
                    @if($rappel->notes)
                        <p class="text-sm text-cendre mt-1">{{ $rappel->notes }}</p>
                    @endif
                </div>
                <form method="POST" action="{{ route('admin.rappels.markDone', $rappel) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-3 py-1.5 bg-green-50 text-green-700 rounded-couture text-xs hover:bg-green-100 transition">
                        Marquer fait
                    </button>
                </form>
            </div>
        @empty
            <x-ui.empty-state message="Aucun rappel en attente." />
        @endforelse
    </div>

    {{-- Add rappel modal --}}
    <x-ui.modal name="add-rappel" title="Nouveau rappel">
        <form method="POST" action="{{ route('admin.rappels.store') }}">
            @csrf
            <x-forms.input label="Titre" name="titre" :required="true" />
            <x-forms.input label="Date de rappel" name="date_rappel" type="date" :required="true" />
            <x-forms.textarea label="Notes" name="notes" rows="3" />
            <x-forms.select label="Commande (optionnel)" name="commande_id" :options="isset($commandes) ? $commandes->pluck('reference', 'id')->toArray() : []" />
            <div class="flex justify-end pt-4">
                <button type="submit" class="px-4 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition text-sm">
                    Creer
                </button>
            </div>
        </form>
    </x-ui.modal>
@endsection
