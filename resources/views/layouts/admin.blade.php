<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Administration') - Atelier Couture</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-ivoire text-charbon min-h-screen" x-data="{ adminMenuOpen: false }" @keydown.escape.window="adminMenuOpen = false">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <x-navigation.admin-sidebar />

        {{-- Main content area --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Top header --}}
            <header class="bg-white border-b border-lin px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between gap-3">
                <div class="flex min-w-0 items-center gap-3">
                    <button type="button" @click="adminMenuOpen = true" class="lg:hidden inline-flex shrink-0 items-center justify-center w-10 h-10 rounded-couture border border-lin text-charbon hover:bg-sable focus:outline-none focus:ring-2 focus:ring-terracotta-300" aria-label="Ouvrir le menu d'administration" :aria-expanded="adminMenuOpen.toString()">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <h1 class="font-display text-xl sm:text-2xl font-semibold text-charbon truncate">@yield('page-title', 'Administration')</h1>
                </div>
                <div class="flex shrink-0 items-center gap-2 sm:gap-4">
                    <span class="hidden sm:block max-w-40 truncate text-sm text-cendre">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-cendre hover:text-terracotta-500 transition" aria-label="Se déconnecter">
                            <span class="hidden sm:inline">Deconnexion</span><span class="sm:hidden">Sortir</span>
                        </button>
                    </form>
                </div>
            </header>

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="px-4 sm:px-6 pt-4">
                    <x-ui.alert type="success" :message="session('success')" />
                </div>
            @endif

            @if(session('error'))
                <div class="px-4 sm:px-6 pt-4">
                    <x-ui.alert type="error" :message="session('error')" />
                </div>
            @endif

            @if(session('info'))
                <div class="px-4 sm:px-6 pt-4">
                    <x-ui.alert type="info" :message="session('info')" />
                </div>
            @endif

            {{-- Page content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
