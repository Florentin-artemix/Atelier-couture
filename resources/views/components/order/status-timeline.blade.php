@props(['statut'])

@php
use App\Enums\OrderStatus;

$allStatuses = [
    OrderStatus::Precommande,
    OrderStatus::Nouvelle,
    OrderStatus::EnAttenteMesures,
    OrderStatus::EnProduction,
    OrderStatus::Prete,
    OrderStatus::Livree,
];

$currentIndex = array_search($statut, $allStatuses);
$isCancelled = $statut === OrderStatus::Annulee;
@endphp

<div class="relative">
    @if($isCancelled)
        <div class="text-center py-4">
            <x-ui.badge color="red" label="Commande annulee" />
        </div>
    @else
        <div class="flex items-center justify-between">
            @foreach($allStatuses as $index => $status)
                <div class="flex flex-col items-center flex-1">
                    {{-- Circle --}}
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium
                        {{ $index <= $currentIndex ? 'bg-terracotta-500 text-white' : 'bg-sable text-cendre' }}">
                        @if($index < $currentIndex)
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </div>
                    {{-- Label --}}
                    <span class="mt-2 text-xs text-center {{ $index <= $currentIndex ? 'text-charbon font-medium' : 'text-cendre' }}">
                        {{ $status->label() }}
                    </span>
                </div>
                @if(!$loop->last)
                    {{-- Connector line --}}
                    <div class="flex-1 h-0.5 {{ $index < $currentIndex ? 'bg-terracotta-500' : 'bg-sable' }} mx-1 mt-[-1rem]"></div>
                @endif
            @endforeach
        </div>
    @endif
</div>
