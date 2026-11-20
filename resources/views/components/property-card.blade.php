@props(['property', 'layout' => 'grid'])
@php($url = route('properties.show', $property))
<article {{ $attributes->merge(['class' => 'group relative overflow-hidden rounded-3xl bg-white shadow-soft transition duration-300 hover:-translate-y-1 hover:shadow-lift '.($layout === 'list' ? 'sm:flex' : '')]) }}>
    <a href="{{ $url }}" class="relative block overflow-hidden {{ $layout === 'list' ? 'aspect-[4/3] sm:aspect-auto sm:w-80 sm:shrink-0' : 'aspect-[4/3]' }}">
        <img src="{{ $property->coverUrl() }}" alt="{{ $property->title }}" loading="lazy"
            class="size-full object-cover transition duration-700 group-hover:scale-105">
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-royal-950/50 to-transparent"></div>
        <div class="absolute left-4 top-4 flex flex-wrap gap-1.5">
            <span class="rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-royal-800 backdrop-blur">{{ $property->offerLabel() }}</span>
            @if ($property->is_featured)
                <span class="rounded-full bg-gold-400 px-3 py-1 text-xs font-semibold text-royal-950">Featured</span>
            @endif
            @unless ($property->isAvailable())
                <span class="rounded-full bg-royal-950/80 px-3 py-1 text-xs font-semibold text-white">{{ $property->availabilityLabel() }}</span>
            @endunless
        </div>
        <p class="absolute bottom-3 left-4 text-lg font-semibold text-white drop-shadow">
            {{ $property->formattedPrice() }}
            @if ($period = $property->periodLabel())<span class="text-sm font-normal text-white/80">{{ $period }}</span>@endif
        </p>
    </a>

    <button type="button" x-data @click="$store.saved.toggle({{ $property->id }})"
        class="absolute right-4 top-4 z-10 grid size-10 place-items-center rounded-full bg-white/95 text-royal-800 shadow-soft backdrop-blur transition hover:scale-110"
        :class="$store.saved.has({{ $property->id }}) && 'text-gold-500'"
        :aria-pressed="$store.saved.has({{ $property->id }})" aria-label="Save {{ $property->title }}">
        <x-icon name="heart" class="size-5" ::class="$store.saved.has({{ $property->id }}) && 'fill-current'" />
    </button>

    <div class="flex flex-1 flex-col p-5 {{ $layout === 'list' ? 'sm:p-6' : '' }}">
        <p class="text-xs font-medium uppercase tracking-wider text-gold-600">{{ $property->category?->name ?? 'Property' }} · {{ $property->location }}</p>
        <h3 class="mt-1.5 font-display text-lg font-semibold leading-snug text-royal-950">
            <a href="{{ $url }}" class="after:absolute after:inset-0 after:content-[''] focus:outline-none">{{ $property->title }}</a>
        </h3>
        @if ($layout === 'list' && $property->summary)
            <p class="mt-2 line-clamp-2 text-sm text-ink/60">{{ $property->summary }}</p>
        @endif
        <p class="mt-auto pt-4 text-sm text-ink/60">{{ $property->factsLine() }}@if ($property->furnished) · Furnished @endif</p>
    </div>
</article>
