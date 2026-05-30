@props(['percentage' => 0, 'color' => 'terracotta'])

@php
$bgColors = [
    'terracotta' => 'bg-terracotta-500',
    'green' => 'bg-green-500',
    'blue' => 'bg-blue-500',
    'red' => 'bg-red-500',
    'yellow' => 'bg-yellow-500',
];
$bgClass = $bgColors[$color] ?? $bgColors['terracotta'];
@endphp

<div class="w-full bg-sable rounded-full h-2.5">
    <div class="{{ $bgClass }} h-2.5 rounded-full transition-all duration-300" style="width: {{ min(100, max(0, $percentage)) }}%"></div>
</div>
