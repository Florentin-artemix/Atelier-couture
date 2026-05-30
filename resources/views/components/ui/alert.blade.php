@props(['type' => 'info', 'message' => ''])

@php
$colors = [
    'success' => 'bg-green-50 border-green-400 text-green-800',
    'error' => 'bg-red-50 border-red-400 text-red-800',
    'warning' => 'bg-yellow-50 border-yellow-400 text-yellow-800',
    'info' => 'bg-blue-50 border-blue-400 text-blue-800',
];
$colorClass = $colors[$type] ?? $colors['info'];
@endphp

<div x-data="{ show: true }" x-show="show" x-transition class="border-l-4 p-4 rounded-couture {{ $colorClass }}" role="alert">
    <div class="flex items-center justify-between">
        <p class="text-sm">{{ $message }}</p>
        <button @click="show = false" class="ml-4 opacity-70 hover:opacity-100">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
</div>
