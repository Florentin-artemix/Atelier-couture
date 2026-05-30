@extends('layouts.admin')

@section('page-title', 'Mesures - ' . $client->nom)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.clients.show', $client) }}" class="text-sm text-cendre hover:text-terracotta-500">&larr; Retour au client</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Add measurement form --}}
        <x-ui.card title="Ajouter une mesure">
            <form method="POST" action="{{ route('admin.mesures.store') }}">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->id }}">

                <x-forms.select
                    label="Type de mesure"
                    name="mesure_type_id"
                    :options="$mesureTypes->pluck('nom', 'id')->toArray()"
                    :required="true"
                />

                <x-forms.input label="Valeur (cm)" name="valeur" type="number" :required="true" />
                <x-forms.input label="Date de prise" name="date_prise" type="date" />

                <div class="pt-4">
                    <button type="submit" class="px-4 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition text-sm">
                        Enregistrer
                    </button>
                </div>
            </form>
        </x-ui.card>

        {{-- Existing measurements --}}
        <x-ui.card title="Mesures enregistrees">
            @if(isset($mesures) && $mesures->count())
                <div class="space-y-2">
                    @foreach($mesures as $mesure)
                        <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-lin' : '' }}">
                            <div>
                                <span class="font-medium text-charbon">{{ $mesure->mesureType->nom ?? '-' }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-medium text-charbon">{{ $mesure->valeur }} cm</span>
                                @if($mesure->date_prise)
                                    <p class="text-xs text-cendre">{{ $mesure->date_prise->format('d/m/Y') }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <x-ui.empty-state message="Aucune mesure enregistree." />
            @endif
        </x-ui.card>
    </div>
@endsection
