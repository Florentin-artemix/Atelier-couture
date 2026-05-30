@props(['label', 'name', 'accept' => 'image/*', 'multiple' => false, 'current' => null])

<div class="mb-4" x-data="{ preview: '{{ $current ?? '' }}' }">
    <label for="{{ $name }}" class="block text-sm font-medium text-charbon mb-1">
        {{ $label }}
    </label>

    {{-- Preview --}}
    <div x-show="preview" class="mb-3">
        <img :src="preview" alt="Aperçu" class="w-40 h-40 object-cover rounded-couture border border-lin">
    </div>

    {{-- Upload zone --}}
    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-lin border-dashed rounded-couture hover:border-terracotta-300 transition">
        <div class="space-y-1 text-center">
            <svg class="mx-auto h-12 w-12 text-cendre" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="flex text-sm text-cendre">
                <label for="{{ $name }}" class="relative cursor-pointer text-terracotta-500 hover:text-terracotta-600 font-medium">
                    <span>Choisir un fichier</span>
                    <input
                        id="{{ $name }}"
                        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
                        type="file"
                        accept="{{ $accept }}"
                        @if($multiple) multiple @endif
                        class="sr-only"
                        @change="
                            const file = $event.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = (e) => { preview = e.target.result; };
                                reader.readAsDataURL(file);
                            }
                        "
                        {{ $attributes }}
                    >
                </label>
                <p class="pl-1">ou glisser-deposer</p>
            </div>
            <p class="text-xs text-cendre">PNG, JPG, WEBP jusqu'a 5MB</p>
        </div>
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
