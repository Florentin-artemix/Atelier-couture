@extends('layouts.public')

@section('title', 'Diva Couture - Couture sur mesure')

@section('content')
    {{-- Hero section --}}
    <section class="relative bg-sable">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32">
            <div class="text-center">
                <h1 class="font-display text-4xl md:text-6xl font-semibold text-charbon">
                    L'elegance sur mesure
                </h1>
                <p class="mt-4 text-lg md:text-xl text-cendre max-w-2xl mx-auto">
                    Creations uniques, confectionnees avec passion et savoir-faire pour sublimer votre style.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('public.catalogue.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition font-medium">
                        Voir le catalogue
                    </a>
                    <a href="{{ route('public.portfolio.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-lin text-charbon rounded-couture hover:bg-white transition font-medium">
                        Nos realisations
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Comment ca marche --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <h2 class="font-display text-3xl font-semibold text-charbon text-center">Comment ca marche</h2>
        <p class="mt-2 text-cendre text-center">Trois etapes simples pour votre creation sur mesure</p>

        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Step 1 --}}
            <div class="text-center">
                <div class="w-16 h-16 mx-auto bg-terracotta-50 rounded-full flex items-center justify-center">
                    <span class="text-terracotta-500 font-display text-2xl font-bold">1</span>
                </div>
                <h3 class="mt-4 font-display text-xl font-semibold text-charbon">Choisissez votre modele</h3>
                <p class="mt-2 text-cendre">Parcourez notre catalogue et selectionnez le modele qui vous inspire.</p>
            </div>

            {{-- Step 2 --}}
            <div class="text-center">
                <div class="w-16 h-16 mx-auto bg-terracotta-50 rounded-full flex items-center justify-center">
                    <span class="text-terracotta-500 font-display text-2xl font-bold">2</span>
                </div>
                <h3 class="mt-4 font-display text-xl font-semibold text-charbon">Prenez rendez-vous</h3>
                <p class="mt-2 text-cendre">Nous prenons vos mesures et discutons des details de votre commande.</p>
            </div>

            {{-- Step 3 --}}
            <div class="text-center">
                <div class="w-16 h-16 mx-auto bg-terracotta-50 rounded-full flex items-center justify-center">
                    <span class="text-terracotta-500 font-display text-2xl font-bold">3</span>
                </div>
                <h3 class="mt-4 font-display text-xl font-semibold text-charbon">Recevez votre creation</h3>
                <p class="mt-2 text-cendre">Suivez l'avancement et recuperez votre tenue prete a porter.</p>
            </div>
        </div>
    </section>

    {{-- CTA section --}}
    <section class="bg-white border-t border-lin">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h2 class="font-display text-3xl font-semibold text-charbon">Pret a commander ?</h2>
            <p class="mt-2 text-cendre">Decouvrez nos modeles et passez votre precommande en ligne.</p>
            <a href="{{ route('public.catalogue.index') }}" class="mt-6 inline-flex items-center px-6 py-3 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition font-medium">
                Precommander maintenant
            </a>
        </div>
    </section>
@endsection
