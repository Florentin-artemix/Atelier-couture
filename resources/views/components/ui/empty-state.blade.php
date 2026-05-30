@props(['message' => 'Aucun resultat', 'icon' => null])

<div class="text-center py-12">
    @if($icon)
        <div class="text-cendre text-4xl mb-4">
            {!! $icon !!}
        </div>
    @endif
    <p class="text-cendre text-lg">{{ $message }}</p>
    @if(isset($slot) && !$slot->isEmpty())
        <div class="mt-4">
            {{ $slot }}
        </div>
    @endif
</div>
