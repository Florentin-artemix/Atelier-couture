@extends('layouts.admin')

@section('page-title', 'Clients')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        {{-- Search --}}
        <form method="GET" action="{{ route('admin.clients.index') }}" class="flex flex-col sm:flex-row gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un client..."
                   class="w-full sm:w-auto px-3 py-2 border border-lin rounded-couture bg-white text-charbon placeholder-cendre focus:outline-none focus:ring-2 focus:ring-terracotta-500 text-sm">
            <button type="submit" class="px-4 py-2 bg-white border border-lin rounded-couture text-sm text-cendre hover:bg-sable transition">
                Rechercher
            </button>
        </form>
        <a href="{{ route('admin.clients.create') }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition text-sm">
            + Nouveau client
        </a>
    </div>

    <div class="bg-white rounded-couture shadow-sm border border-lin overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-sm">
                <thead class="bg-sable">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Nom</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Telephone</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Commandes</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-lin">
                    @forelse($clients as $client)
                        <tr class="hover:bg-sable/50 transition">
                            <td class="px-4 py-3 font-medium text-charbon">{{ $client->nom }}</td>
                            <td class="px-4 py-3 text-cendre">{{ $client->telephone ?? '-' }}</td>
                            <td class="px-4 py-3 text-cendre">{{ $client->email ?? '-' }}</td>
                            <td class="px-4 py-3 text-cendre">{{ $client->commandes_count ?? 0 }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.clients.show', $client) }}" class="text-terracotta-500 hover:text-terracotta-600">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-cendre">Aucun client trouve.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($clients->hasPages())
        <div class="mt-6">
            {{ $clients->links() }}
        </div>
    @endif
@endsection
