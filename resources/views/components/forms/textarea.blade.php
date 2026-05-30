@props(['label', 'name', 'rows' => 4, 'value' => '', 'required' => false])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-charbon mb-1">
        {{ $label }}
        @if($required)
            <span class="text-terracotta-500">*</span>
        @endif
    </label>
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        @if($required) required @endif
        class="w-full px-3 py-2 border border-lin rounded-couture bg-white text-charbon placeholder-cendre focus:outline-none focus:ring-2 focus:ring-terracotta-500 focus:border-transparent transition @error($name) border-red-400 @enderror"
        {{ $attributes }}
    >{{ old($name, $value) }}</textarea>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
