<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Espace Client') - Atelier Couture</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-ivoire text-charbon min-h-screen flex flex-col" x-data="{ clientMenuOpen: false }">
    {{-- Navigation --}}
    <nav class="bg-white border-b border-lin">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center gap-3">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="font-display text-2xl font-semibold text-charbon">
                        Atelier Couture
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('client.orders.index') }}" class="text-cendre hover:text-terracotta-500 transition">Mes commandes</a>
                    <a href="{{ route('client.profile.show') }}" class="text-cendre hover:text-terracotta-500 transition">Mon profil</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-cendre hover:text-terracotta-500 transition">Deconnexion</button>
                    </form>
                </div>
                <button type="button" @click="clientMenuOpen = !clientMenuOpen" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-couture border border-lin text-charbon hover:bg-sable" aria-label="Ouvrir le menu" :aria-expanded="clientMenuOpen.toString()">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
            </div>
        </div>
        <div x-cloak x-show="clientMenuOpen" x-transition class="md:hidden border-t border-lin">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('client.orders.index') }}" class="block rounded-couture px-3 py-2 text-cendre hover:bg-sable hover:text-terracotta-500">Mes commandes</a>
                <a href="{{ route('client.profile.show') }}" class="block rounded-couture px-3 py-2 text-cendre hover:bg-sable hover:text-terracotta-500">Mon profil</a>
                <form method="POST" action="{{ route('logout') }}">@csrf <button type="submit" class="block w-full rounded-couture px-3 py-2 text-left text-cendre hover:bg-sable hover:text-terracotta-500">Deconnexion</button></form>
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <x-ui.alert type="success" :message="session('success')" />
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <x-ui.alert type="error" :message="session('error')" />
        </div>
    @endif

    {{-- Main content --}}
    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-lin">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-sm text-cendre">
            &copy; {{ date('Y') }} Atelier Couture. Tous droits reserves.
        </div>
    </footer>
</body>
</html>
