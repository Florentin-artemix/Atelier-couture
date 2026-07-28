@props(['title' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-couture shadow-sm border border-lin overflow-hidden']) }}>
    @if($title)
        <div class="px-4 sm:px-6 py-4 border-b border-lin">
            <h3 class="font-display text-lg font-semibold text-charbon">{{ $title }}</h3>
        </div>
    @endif
    <div class="p-4 sm:p-6">
        {{ $slot }}
    </div>
</div>
