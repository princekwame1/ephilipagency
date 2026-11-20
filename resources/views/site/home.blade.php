@php $s = \App\Models\Setting::values(); @endphp
{{-- All wording, photos and section switches come from Admin → Site content (App\Support\SiteContent). --}}
<x-layouts.site overlay>
    {{-- Hero --}}
    <section class="relative isolate flex min-h-[92vh] items-end bg-royal-950 pb-16 pt-36 sm:items-center sm:pb-24">
        {{-- Background is clipped on its own layer so the search dropdowns can open past the hero --}}
        <div class="absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
            @if ($s['hero_image_path'])
                <img src="{{ \App\Models\Setting::heroImageUrl() }}" alt="" fetchpriority="high" class="absolute inset-0 size-full object-cover">
            @else
                {{-- Default photo: smaller file on phones, framed on the villa (right of centre) --}}
                <img src="{{ asset('images/hero-villa.webp') }}" srcset="{{ asset('images/hero-villa-900.webp') }} 900w, {{ asset('images/hero-villa.webp') }} 1536w"
                    sizes="100vw" width="1536" height="1024" alt="" fetchpriority="high" decoding="async"
                    class="absolute inset-0 size-full object-cover object-[68%_center] animate-hero-zoom">
            @endif
            {{-- Shading keeps the headline and search readable while the photo stays bright on the right --}}
            <div class="absolute inset-0 bg-gradient-to-r from-royal-950/90 via-royal-950/55 to-royal-950/0 max-sm:from-royal-950/80 max-sm:via-royal-950/65 max-sm:to-royal-950/40"></div>
            <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-royal-950/70 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-royal-950/85 to-transparent"></div>
            <div class="absolute -bottom-32 -left-24 size-[28rem] rounded-full bg-gold-500/15 blur-3xl"></div>
        </div>

        <div class="page">
            <div class="max-w-3xl animate-fade-up">
                @if ($s['tagline'])<p class="eyebrow text-gold-300">{{ $s['tagline'] }}</p>@endif
                <h1 class="mt-4 font-display text-5xl font-medium leading-[1.05] text-white sm:text-6xl lg:text-7xl">{{ site('hero_title') }}</h1>
                @if (site('hero_text'))<p class="mt-6 max-w-xl text-lg leading-relaxed text-white/75">{{ site('hero_text') }}</p>@endif
            </div>

            {{-- Search --}}
            <form action="{{ route('properties.index') }}" method="get" x-data="{ offer: '' }"
                class="mt-10 max-w-4xl animate-fade-up rounded-[2rem] bg-white p-3 shadow-lift [animation-delay:150ms]">
                <input type="hidden" name="offer" :value="offer" :disabled="!offer">
                <div class="flex gap-1 overflow-x-auto px-2 pb-3 pt-1 scrollbar-none" role="tablist">
                    @foreach (['' => 'All', 'rent' => 'Rent', 'sale' => 'Buy', 'short_stay' => 'Short stay', 'lease' => 'Lease'] as $key => $label)
                        <button type="button" role="tab" @click="offer = '{{ $key }}'" :aria-selected="offer === '{{ $key }}'"
                            class="shrink-0 rounded-full px-4 py-2 text-sm font-medium transition"
                            :class="offer === '{{ $key }}' ? 'bg-royal-800 text-white' : 'text-ink/60 hover:bg-royal-50'">{{ $label }}</button>
                    @endforeach
                </div>
                <div class="grid gap-2 sm:grid-cols-[1.5fr_1fr_1fr_auto]">
                    <label class="sr-only" for="hero-q">Search</label>
                    <div class="relative">
                        <x-icon name="search" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-royal-600" />
                        <input id="hero-q" name="q" type="search" class="input h-full pl-10" placeholder="{{ site('home_search_placeholder') }}">
                    </div>
                    <x-select id="hero-category" name="category" label="Type" placeholder="Any type" icon="home"
                        :options="$categories->pluck('name', 'slug')->all()" />
                    <x-select id="hero-beds" name="beds" label="Bedrooms" placeholder="Any beds" icon="bed"
                        :options="collect([1, 2, 3, 4, 5])->mapWithKeys(fn ($n) => [$n => $n.'+ beds'])->all()" />
                    <button class="btn-primary h-full px-7 py-3">
                        <x-icon name="search" class="size-4" /> {{ site('home_search_button') }}
                    </button>
                </div>
            </form>

            <dl class="mt-12 flex flex-wrap gap-x-12 gap-y-6 text-white animate-fade-up [animation-delay:300ms]">
                <div><dt class="text-sm text-white/60">{{ site('stat_listings_label') }}</dt><dd class="font-display text-3xl">{{ number_format($totalListings) }}</dd></div>
                @if ($s['stat_clients'])<div><dt class="text-sm text-white/60">{{ site('stat_clients_label') }}</dt><dd class="font-display text-3xl">{{ $s['stat_clients'] }}</dd></div>@endif
                @if ($s['stat_years'])<div><dt class="text-sm text-white/60">{{ site('stat_years_label') }}</dt><dd class="font-display text-3xl">{{ $s['stat_years'] }}</dd></div>@endif
            </dl>
        </div>
    </section>

    {{-- Categories --}}
    @if (site_shows('home_categories_show'))
        <section class="page pt-20">
            <div class="flex flex-wrap items-end justify-between gap-4" data-reveal>
                <div>
                    @if (site('home_categories_eyebrow'))<p class="eyebrow">{{ site('home_categories_eyebrow') }}</p>@endif
                    <h2 class="mt-2 font-display text-3xl text-royal-950 sm:text-4xl">{{ site('home_categories_title') }}</h2>
                </div>
                @if (site('home_categories_button'))<a href="{{ route('properties.index') }}" class="btn-ghost">{{ site('home_categories_button') }} <x-icon name="arrow-right" class="size-4" /></a>@endif
            </div>
            <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                @foreach ($categories as $i => $category)
                    <a href="{{ route('properties.index', ['category' => $category->slug]) }}" data-reveal style="--delay: {{ $i * 40 }}ms"
                        class="rounded-2xl bg-white p-5 shadow-soft ring-1 ring-royal-100 transition hover:-translate-y-0.5 hover:ring-gold-300">
                        <p class="font-display text-lg text-royal-950">{{ $category->name }}</p>
                        <p class="mt-1 text-sm text-ink/50">{{ $category->properties_count }} {{ Str::plural('listing', $category->properties_count) }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Featured --}}
    @if (site_shows('home_featured_show') && $featured->isNotEmpty())
        <section class="page pt-24">
            <div data-reveal>
                @if (site('home_featured_eyebrow'))<p class="eyebrow">{{ site('home_featured_eyebrow') }}</p>@endif
                <h2 class="mt-2 font-display text-3xl text-royal-950 sm:text-4xl">{{ site('home_featured_title') }}</h2>
            </div>
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featured as $i => $property)
                    <x-property-card :property="$property" data-reveal style="--delay: {{ ($i % 3) * 80 }}ms" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- Why us --}}
    @if (site_shows('home_why_show'))
        <section class="relative isolate mt-24 overflow-hidden bg-royal-950 py-24 text-white sm:py-28">
            {{-- Background photo, tinted purple so the text and cards stay readable --}}
            @if ($s['why_image_path'])
                <img src="{{ \App\Models\Setting::imageUrl('why_image_path') }}" alt="" loading="lazy" decoding="async" class="absolute inset-0 -z-20 size-full object-cover">
            @else
                <img src="{{ asset('images/why-estate.webp') }}" srcset="{{ asset('images/why-estate-900.webp') }} 900w, {{ asset('images/why-estate.webp') }} 1672w"
                    sizes="100vw" width="1672" height="941" alt="" loading="lazy" decoding="async"
                    class="absolute inset-0 -z-20 size-full object-cover object-[60%_center]">
            @endif
            <div class="absolute inset-0 -z-10 bg-gradient-to-r from-royal-950/95 via-royal-950/80 to-royal-950/55 max-lg:from-royal-950/75 max-lg:via-royal-950/60 max-lg:to-royal-950/60"></div>
            <div class="absolute inset-x-0 top-0 -z-10 h-32 bg-gradient-to-b from-royal-950/70 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 -z-10 h-32 bg-gradient-to-t from-royal-950/70 to-transparent"></div>

            <div class="page grid items-center gap-12 lg:grid-cols-[1fr_1.6fr]">
                <div data-reveal>
                    @if (site('home_why_eyebrow'))<p class="eyebrow text-gold-300">{{ site('home_why_eyebrow') }}</p>@endif
                    <h2 class="mt-3 font-display text-4xl leading-tight sm:text-5xl">{{ site('home_why_title') }}</h2>
                    @if (site('home_why_text'))<p class="mt-5 text-white/75">{{ site('home_why_text') }}</p>@endif
                    @if (site('home_why_button'))<a href="{{ route('about') }}" class="btn-gold mt-8">{{ site('home_why_button') }}</a>@endif
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    {{-- Points with a blank heading are left out --}}
                    @foreach (collect(range(1, 4))->map(fn ($n) => [site("home_why_{$n}_title"), site("home_why_{$n}_text")])->filter(fn ($p) => $p[0] !== '')->values() as $i => [$heading, $text])
                        <div data-reveal style="--delay: {{ $i * 80 }}ms"
                            class="rounded-3xl bg-royal-950/55 p-6 ring-1 ring-white/10 backdrop-blur-md transition duration-300 hover:-translate-y-1 hover:bg-royal-950/70 hover:ring-gold-400/40">
                            <p class="font-display text-4xl text-gold-400">0{{ $i + 1 }}</p>
                            <h3 class="mt-3 text-lg font-semibold">{{ $heading }}</h3>
                            @if ($text)<p class="mt-2 text-sm leading-relaxed text-white/70">{{ $text }}</p>@endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Latest --}}
    @if (site_shows('home_latest_show') && $latest->isNotEmpty())
        <section class="pt-24" x-data>
            <div class="page flex flex-wrap items-end justify-between gap-4" data-reveal>
                <div>
                    @if (site('home_latest_eyebrow'))<p class="eyebrow">{{ site('home_latest_eyebrow') }}</p>@endif
                    <h2 class="mt-2 font-display text-3xl text-royal-950 sm:text-4xl">{{ site('home_latest_title') }}</h2>
                </div>
                <div class="flex gap-2">
                    <button type="button" class="grid size-11 place-items-center rounded-full bg-white shadow-soft hover:bg-royal-50" @click="$refs.track.scrollBy({ left: -340, behavior: 'smooth' })" aria-label="Scroll back"><x-icon name="chevron-left" /></button>
                    <button type="button" class="grid size-11 place-items-center rounded-full bg-white shadow-soft hover:bg-royal-50" @click="$refs.track.scrollBy({ left: 340, behavior: 'smooth' })" aria-label="Scroll forward"><x-icon name="chevron-right" /></button>
                </div>
            </div>
            <div x-ref="track" class="mt-8 flex snap-x snap-mandatory gap-6 overflow-x-auto scroll-px-5 px-5 pb-8 scrollbar-none sm:scroll-px-8 sm:px-8 xl:px-[max(2rem,calc((100vw-80rem)/2+2rem))]">
                @foreach ($latest as $property)
                    <x-property-card :property="$property" class="w-[19rem] shrink-0 snap-start sm:w-[21rem]" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- Neighbourhoods --}}
    @if (site_shows('home_areas_show') && $locations->isNotEmpty())
        <section class="page pt-16">
            <div data-reveal>
                @if (site('home_areas_eyebrow'))<p class="eyebrow">{{ site('home_areas_eyebrow') }}</p>@endif
                <h2 class="mt-2 font-display text-3xl text-royal-950 sm:text-4xl">{{ site('home_areas_title') }}</h2>
            </div>
            <div class="mt-8 grid grid-cols-2 gap-3 md:grid-cols-4">
                @foreach ($locations as $i => $loc)
                    <a href="{{ route('properties.index', ['location' => $loc->location]) }}" data-reveal style="--delay: {{ $i * 40 }}ms"
                        class="group flex items-center justify-between rounded-2xl bg-white px-5 py-4 shadow-soft transition hover:bg-gold-50">
                        <span>
                            <span class="block font-medium text-royal-950">{{ $loc->location }}</span>
                            <span class="text-sm text-ink/50">{{ $loc->total }} {{ Str::plural('home', $loc->total) }}</span>
                        </span>
                        <x-icon name="arrow-right" class="size-4 text-gold-500 transition group-hover:translate-x-1" />
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Owners CTA --}}
    @if (site_shows('home_owners_show'))
        <section class="page pt-24">
            <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-royal-800 via-royal-900 to-royal-950 px-8 py-14 text-white sm:px-14" data-reveal>
                <div class="absolute -right-20 -top-20 size-80 rounded-full bg-gold-400/25 blur-3xl"></div>
                <div class="relative grid items-center gap-8 lg:grid-cols-[1.5fr_1fr]">
                    <div>
                        @if (site('home_owners_eyebrow'))<p class="eyebrow text-gold-300">{{ site('home_owners_eyebrow') }}</p>@endif
                        <h2 class="mt-3 font-display text-3xl leading-tight sm:text-4xl">{{ site('home_owners_title') }}</h2>
                        @if (site('home_owners_text'))<p class="mt-4 max-w-xl text-white/70">{{ site('home_owners_text') }}</p>@endif
                    </div>
                    <div class="flex flex-wrap gap-3 lg:justify-end">
                        @if (site('home_owners_button'))<a href="{{ route('submit') }}" class="btn-gold px-7 py-3.5">{{ site('home_owners_button') }}</a>@endif
                        @if (site('home_owners_button2'))<a href="{{ route('contact') }}" class="btn-light px-7 py-3.5">{{ site('home_owners_button2') }}</a>@endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Testimonials --}}
    @if (site_shows('home_reviews_show') && $testimonials->isNotEmpty())
        <section class="page pt-24">
            <div class="text-center" data-reveal>
                @if (site('home_reviews_eyebrow'))<p class="eyebrow">{{ site('home_reviews_eyebrow') }}</p>@endif
                <h2 class="mt-2 font-display text-3xl text-royal-950 sm:text-4xl">{{ site('home_reviews_title') }}</h2>
            </div>
            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @foreach ($testimonials->take(3) as $i => $t)
                    <figure class="card flex flex-col p-7" data-reveal style="--delay: {{ $i * 80 }}ms">
                        <p class="text-gold-500" aria-label="{{ $t->rating }} out of 5">{{ str_repeat('★', $t->rating) }}<span class="text-royal-100">{{ str_repeat('★', 5 - $t->rating) }}</span></p>
                        <blockquote class="mt-4 flex-1 font-display text-lg leading-relaxed text-royal-950">“{{ $t->quote }}”</blockquote>
                        <figcaption class="mt-6 text-sm"><span class="font-semibold text-ink">{{ $t->name }}</span>@if ($t->role)<span class="text-ink/50"> · {{ $t->role }}</span>@endif</figcaption>
                    </figure>
                @endforeach
            </div>
            @if (site('home_reviews_button'))
                <p class="mt-8 text-center"><a href="{{ route('review') }}" class="btn-ghost"><x-icon name="star" class="size-4" /> {{ site('home_reviews_button') }}</a></p>
            @endif
        </section>
    @endif
</x-layouts.site>
