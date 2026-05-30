@props(['label', 'name', 'options' => [], 'selected' => null, 'required' => false])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-charbon mb-1">
        {{ $label }}
        @if($required)
            <span class="text-terracotta-500">*</span>
        @endif
    </label>
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        @if($required) required @endif
        class="w-full px-3 py-2 border border-lin rounded-couture bg-white text-charbon focus:outline-none focus:ring-2 focus:ring-terracotta-500 focus:border-transparent transition @error($name) border-red-400 @enderror"
        {{ $attributes }}
    >
        <option value="">-- Choisir --</option>
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected(old($name, $selected) == $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
