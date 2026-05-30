@extends('layouts.admin')
@section('page-title', 'Modifier commande ' . $commande->reference)

@section('content')
<a href="{{ route('admin.commandes.show', $commande) }}" class="text-sm text-cendre hover:text-terracotta-500 mb-6 inline-block">&larr; Retour</a>

<x-ui.card title="Modifier la commande {{ $commande->reference }}">
    <form action="{{ route('admin.commandes.update', $commande) }}" method="POST">
        @csrf @method('PUT')
        <div class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-forms.input name="date_livraison_prevue" label="Date livraison prévue" type="date" required :value="$commande->date_livraison_prevue->format('Y-m-d')" />
                <x-forms.input name="prix_final" label="Prix final" type="number" step="0.01" :value="$commande->prix_final" placeholder="Laisser vide = non fixé" />
            </div>
            <x-forms.textarea name="notes_internes" label="Notes internes" :value="$commande->notes_internes" rows="3" />
            <x-forms.textarea name="notes_client" label="Notes client" :value="$commande->notes_client" rows="3" />
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.commandes.show', $commande) }}" class="btn-secondary">Annuler</a>
            <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
    </form>
</x-ui.card>
@endsection
