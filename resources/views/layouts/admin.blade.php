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
<body class="font-body bg-ivoire text-charbon min-h-screen">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <x-navigation.admin-sidebar />

        {{-- Main content area --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Top header --}}
            <header class="bg-white border-b border-lin px-6 py-4 flex items-center justify-between">
                <h1 class="font-display text-xl font-semibold text-charbon">@yield('page-title', 'Administration')</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-cendre">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-cendre hover:text-terracotta-500 transition">
                            Deconnexion
                        </button>
                    </form>
                </div>
            </header>

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="px-6 pt-4">
                    <x-ui.alert type="success" :message="session('success')" />
                </div>
            @endif

            @if(session('error'))
                <div class="px-6 pt-4">
                    <x-ui.alert type="error" :message="session('error')" />
                </div>
            @endif

            {{-- Page content --}}
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
