@extends('layouts.admin')

@section('page-title', 'Rappels')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-cendre">Rappels en attente</p>
    </div>

    {{-- Formulaire de creation --}}
    <div class="mb-8">
        <x-ui.card title="Creer un rappel">
            <form method="POST" action="{{ route('admin.rappels.store') }}">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-forms.input label="Titre" name="titre" required placeholder="Ex: Appeler Mme Diallo" />
                    <x-forms.input label="Date echeance" name="date_echeance" type="date" required />
                </div>
                <x-forms.textarea label="Description (optionnel)" name="description" rows="2" />
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition text-sm">
                        Creer le rappel
                    </button>
                </div>
            </form>
        </x-ui.card>
    </div>

    {{-- Liste des rappels --}}
    <div class="space-y-3">
        @forelse($rappels as $rappel)
            <div class="bg-white rounded-couture shadow-sm border border-lin p-4 flex flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <p class="font-medium text-charbon">{{ $rappel->titre }}</p>
                    <p class="text-sm text-cendre">
                        Echeance : {{ $rappel->date_echeance ? $rappel->date_echeance->format('d/m/Y') : '-' }}
                        @if($rappel->commande)
                            - Commande {{ $rappel->commande->reference }}
                        @endif
                    </p>
                    @if($rappel->description)
                        <p class="text-sm text-cendre mt-1">{{ $rappel->description }}</p>
                    @endif
                </div>
                <form method="POST" action="{{ route('admin.rappels.markDone', $rappel) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="whitespace-nowrap px-3 py-1.5 bg-green-50 text-green-700 rounded-couture text-xs hover:bg-green-100 transition">
                        Marquer fait
                    </button>
                </form>
            </div>
        @empty
            <x-ui.empty-state message="Aucun rappel en attente." />
        @endforelse
    </div>
@endsection
