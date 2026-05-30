@props(['title' => null])

<div class="bg-white rounded-couture shadow-sm border border-lin overflow-hidden">
    @if($title)
        <div class="px-6 py-4 border-b border-lin">
            <h3 class="font-display text-lg font-semibold text-charbon">{{ $title }}</h3>
        </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
