@extends('layouts.admin')

@section('page-title', 'Nouveau client')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.clients.index') }}" class="text-sm text-cendre hover:text-terracotta-500">&larr; Retour aux clients</a>
    </div>

    <div class="max-w-2xl">
        <x-ui.card title="Creer un client">
            <form method="POST" action="{{ route('admin.clients.store') }}">
                @csrf

                <x-forms.input label="Nom complet" name="nom" :required="true" />
                <x-forms.input label="Telephone" name="telephone" type="tel" :required="true" />
                <x-forms.input label="Email" name="email" type="email" />
                <x-forms.textarea label="Adresse" name="adresse" rows="2" />
                <x-forms.textarea label="Notes" name="notes" rows="3" />

                <div class="pt-4 border-t border-lin">
                    <button type="submit" class="px-6 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition font-medium">
                        Creer le client
                    </button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
