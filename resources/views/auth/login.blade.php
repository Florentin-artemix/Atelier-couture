@extends('layouts.public')

@section('title', 'Connexion - Atelier Couture')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="font-display text-3xl font-semibold text-charbon">Connexion</h1>
            <p class="mt-2 text-cendre">Accedez a votre espace</p>
        </div>

        <div class="bg-white rounded-couture shadow-sm border border-lin p-8">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <x-forms.input label="Adresse email" name="email" type="email" :required="true" />
                <x-forms.input label="Mot de passe" name="password" type="password" :required="true" />
                <x-forms.checkbox label="Se souvenir de moi" name="remember" />

                @if($errors->has('email'))
                    <div class="mb-4">
                        <x-ui.alert type="error" :message="$errors->first('email')" />
                    </div>
                @endif

                <button type="submit" class="w-full px-6 py-3 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition font-medium">
                    Se connecter
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-sm text-cendre">
            <a href="{{ route('home') }}" class="text-terracotta-500 hover:text-terracotta-600">Retour a l'accueil</a>
        </p>
    </div>
</div>
@endsection
