@extends('layouts.client')

@section('title', 'Mon profil')

@section('content')
    <h1 class="font-display text-2xl font-semibold text-charbon">Mon profil</h1>
    <p class="mt-1 text-sm text-cendre">Gerez vos informations personnelles.</p>

    <div class="mt-8 max-w-xl">
        <x-ui.card>
            <form method="POST" action="{{ route('client.profile.update') }}">
                @csrf
                @method('PUT')

                <x-forms.input label="Nom" name="nom" :value="$client->nom ?? ''" :required="true" />
                <x-forms.input label="Telephone" name="telephone" type="tel" :value="$client->telephone ?? ''" :required="true" />
                <x-forms.input label="Email" name="email" type="email" :value="$client->email ?? ''" />
                <x-forms.textarea label="Adresse" name="adresse" rows="2" :value="$client->adresse ?? ''" />

                <div class="pt-4 border-t border-lin">
                    <button type="submit" class="px-6 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition font-medium">
                        Mettre a jour
                    </button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
