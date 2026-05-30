@extends('layouts.admin')

@section('page-title', 'Accessoires')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-cendre">{{ $accessoires->count() }} accessoire(s)</p>
        <a href="{{ route('admin.accessoires.create') }}" class="inline-flex items-center px-4 py-2 bg-terracotta-500 text-white rounded-couture hover:bg-terracotta-600 transition text-sm">
            + Nouvel accessoire
        </a>
    </div>

    <div class="bg-white rounded-couture shadow-sm border border-lin overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-sable">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Nom</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Prix</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Unite</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Actif</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cendre uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-lin">
                    @forelse($accessoires as $accessoire)
                        <tr class="hover:bg-sable/50 transition">
                            <td class="px-4 py-3 font-medium text-charbon">{{ $accessoire->nom }}</td>
                            <td class="px-4 py-3 text-charbon font-medium">{{ number_format($accessoire->prix_unitaire, 0, ',', ' ') }} FC</td>
                            <td class="px-4 py-3 text-cendre">{{ $accessoire->unite }}</td>
                            <td class="px-4 py-3">
                                @if($accessoire->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Actif</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.accessoires.edit', $accessoire) }}" class="text-terracotta-500 hover:text-terracotta-600 text-xs font-medium">Modifier</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-cendre">Aucun accessoire.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
