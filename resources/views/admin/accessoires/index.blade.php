@extends('layouts.admin')

@section('page-title', 'Accessoires')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-cendre">{{ $accessoires->count() }} accessoire(s)</p>
        <button @click="$dispatch('open-modal-add-accessoire')" class="inline-flex items-center px-4 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition text-sm">
            + Nouvel accessoire
        </button>
    </div>

    <div class="bg-white rounded-couture shadow-sm border border-lin overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-sable">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Nom</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Prix</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Actif</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-lin">
                    @forelse($accessoires as $accessoire)
                        <tr class="hover:bg-sable/50 transition">
                            <td class="px-4 py-3 font-medium text-charbon">{{ $accessoire->nom }}</td>
                            <td class="px-4 py-3 text-cendre">{{ number_format($accessoire->prix, 0, ',', ' ') }} FC</td>
                            <td class="px-4 py-3">
                                <x-ui.badge :color="$accessoire->actif ? 'green' : 'gray'" :label="$accessoire->actif ? 'Actif' : 'Inactif'" />
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.accessoires.edit', $accessoire) }}" class="text-terracotta-500 hover:text-terracotta-600 text-xs">Modifier</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-cendre">Aucun accessoire.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add accessoire modal --}}
    <x-ui.modal name="add-accessoire" title="Nouvel accessoire">
        <form method="POST" action="{{ route('admin.accessoires.store') }}">
            @csrf
            <x-forms.input label="Nom" name="nom" :required="true" />
            <x-forms.input label="Prix (FC)" name="prix" type="number" :required="true" />
            <x-forms.textarea label="Description" name="description" rows="2" />
            <div class="flex justify-end pt-4">
                <button type="submit" class="px-4 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition text-sm">
                    Ajouter
                </button>
            </div>
        </form>
    </x-ui.modal>
@endsection
