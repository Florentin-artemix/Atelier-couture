@props(['categories', 'selected' => null])

<div class="flex flex-wrap gap-2">
    <a href="{{ route('public.catalogue.index') }}"
       class="px-4 py-2 text-sm rounded-couture transition
              {{ is_null($selected) ? 'bg-terracotta-500 text-white' : 'bg-white text-cendre border border-lin hover:border-terracotta-300' }}">
        Tous
    </a>
    @foreach($categories as $categorie)
        <a href="{{ route('public.catalogue.index', ['categorie' => $categorie->slug]) }}"
           class="px-4 py-2 text-sm rounded-couture transition
                  {{ $selected == $categorie->id ? 'bg-terracotta-500 text-white' : 'bg-white text-cendre border border-lin hover:border-terracotta-300' }}">
            {{ $categorie->nom }}
        </a>
    @endforeach
</div>
