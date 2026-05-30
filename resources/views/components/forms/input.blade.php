@props(['label', 'name', 'type' => 'text', 'value' => '', 'required' => false])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-charbon mb-1">
        {{ $label }}
        @if($required)
            <span class="text-terracotta-500">*</span>
        @endif
    </label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        @if($required) required @endif
        class="w-full px-3 py-2 border border-lin rounded-couture bg-white text-charbon placeholder-cendre focus:outline-none focus:ring-2 focus:ring-terracotta-500 focus:border-transparent transition @error($name) border-red-400 @enderror"
        {{ $attributes }}
    >
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
