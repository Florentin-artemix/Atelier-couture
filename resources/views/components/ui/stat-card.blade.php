@props(['title', 'value', 'color' => 'blue', 'icon' => ''])

@php
$borderColors = [
    'blue' => 'border-l-blue-500',
    'green' => 'border-l-green-500',
    'red' => 'border-l-red-500',
    'yellow' => 'border-l-yellow-500',
    'terracotta' => 'border-l-terracotta-500',
    'purple' => 'border-l-purple-500',
    'indigo' => 'border-l-indigo-500',
];
$borderClass = $borderColors[$color] ?? $borderColors['blue'];
@endphp

<div class="bg-white rounded-couture shadow-sm border border-lin border-l-4 {{ $borderClass }} p-5">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-cendre">{{ $title }}</p>
            <p class="mt-1 text-2xl font-semibold text-charbon">{{ $value }}</p>
        </div>
        @if($icon)
            <div class="text-cendre text-2xl">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
