@props(['color' => 'gray', 'label' => ''])

@php
$colors = [
    'gray' => 'bg-gray-100 text-gray-700',
    'green' => 'bg-green-100 text-green-700',
    'red' => 'bg-red-100 text-red-700',
    'blue' => 'bg-blue-100 text-blue-700',
    'yellow' => 'bg-yellow-100 text-yellow-700',
    'purple' => 'bg-purple-100 text-purple-700',
    'indigo' => 'bg-indigo-100 text-indigo-700',
    'terracotta' => 'bg-terracotta-100 text-terracotta-700',
];
$colorClass = $colors[$color] ?? $colors['gray'];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
    {{ $label }}
</span>
