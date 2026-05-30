<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Atelier Couture')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-ivoire text-charbon min-h-screen flex flex-col">
    {{-- Navigation --}}
    <nav x-data="{ open: false }" class="bg-white border-b border-lin">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="font-display text-2xl font-semibold text-charbon">
                        Atelier Couture
                    </a>
                </div>

                {{-- Desktop Navigation --}}
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="{{ route('public.catalogue.index') }}" class="text-cendre hover:text-terracotta-500 transition">Catalogue</a>
                    <a href="{{ route('public.portfolio.index') }}" class="text-cendre hover:text-terracotta-500 transition">Portfolio</a>
                    <a href="{{ route('public.suivi.index') }}" class="text-cendre hover:text-terracotta-500 transition">Suivi</a>
                    <a href="{{ route('login') }}" class="text-cendre hover:text-terracotta-500 transition">Connexion</a>
                    <a href="{{ route('public.catalogue.index') }}" class="inline-flex items-center px-4 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition">
                        Precommander
                    </a>
                </div>

                {{-- Mobile hamburger --}}
                <div class="flex items-center md:hidden">
                    <button @click="open = !open" class="text-cendre hover:text-charbon p-2">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="open" x-transition class="md:hidden border-t border-lin">
            <div class="px-4 py-3 space-y-2">
                <a href="{{ route('public.catalogue.index') }}" class="block px-3 py-2 text-cendre hover:text-terracotta-500">Catalogue</a>
                <a href="{{ route('public.portfolio.index') }}" class="block px-3 py-2 text-cendre hover:text-terracotta-500">Portfolio</a>
                <a href="{{ route('public.suivi.index') }}" class="block px-3 py-2 text-cendre hover:text-terracotta-500">Suivi</a>
                <a href="{{ route('login') }}" class="block px-3 py-2 text-cendre hover:text-terracotta-500">Connexion</a>
                <a href="{{ route('public.catalogue.index') }}" class="block px-3 py-2 bg-terracotta-500 text-white rounded-couture text-center">Precommander</a>
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
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-lin mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-display text-lg font-semibold text-charbon">Atelier Couture</h3>
                    <p class="mt-2 text-sm text-cendre">Couture sur mesure, elegance moderne.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-charbon">Contact</h4>
                    <p class="mt-2 text-sm text-cendre">contact@atelier-couture.com</p>
                    <p class="text-sm text-cendre">+33 1 23 45 67 89</p>
                </div>
                <div>
                    <h4 class="font-semibold text-charbon">Liens</h4>
                    <ul class="mt-2 space-y-1">
                        <li><a href="{{ route('public.catalogue.index') }}" class="text-sm text-cendre hover:text-terracotta-500">Catalogue</a></li>
                        <li><a href="{{ route('public.portfolio.index') }}" class="text-sm text-cendre hover:text-terracotta-500">Portfolio</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-4 border-t border-lin text-center text-sm text-cendre">
                &copy; {{ date('Y') }} Atelier Couture. Tous droits reserves.
            </div>
        </div>
    </footer>
</body>
</html>
