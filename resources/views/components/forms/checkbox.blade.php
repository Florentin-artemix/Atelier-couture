@props(['label', 'name', 'checked' => false])

<div class="mb-4 flex items-center">
    <input
        type="checkbox"
        name="{{ $name }}"
        id="{{ $name }}"
        value="1"
        @checked(old($name, $checked))
        class="h-4 w-4 text-terracotta-500 border-lin rounded focus:ring-terracotta-500"
        {{ $attributes }}
    >
    <label for="{{ $name }}" class="ml-2 text-sm text-charbon">
        {{ $label }}
    </label>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
