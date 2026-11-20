@php($s = \App\Models\Setting::values())
<x-layouts.site overlay title="About us">
    {{-- Wording, photo and section switches: Admin → Site content → About page --}}
    <x-page-hero :eyebrow="site('about_eyebrow')" :title="site('about_title')" />
    <section class="page grid gap-12 pt-16 lg:grid-cols-[1.2fr_1fr] lg:items-center">
        <div data-reveal>
            <div class="prose-estate text-base">
                @foreach (array_filter(preg_split("/\n\s*\n/", trim(site('about')))) as $para)
                    <p>{{ $para }}</p>
                @endforeach
            </div>
        </div>
        <div class="relative" data-reveal style="--delay: 120ms">
            <div class="aspect-[4/5] overflow-hidden rounded-[2.5rem] bg-royal-900 shadow-lift">
                <img src="{{ \App\Models\Setting::imageUrl('about_image_path') }}" alt="" class="size-full object-cover opacity-90">
            </div>
            @if ($s['stat_years'])
                <div class="absolute -bottom-6 -left-6 rounded-3xl bg-gold-400 px-7 py-5 text-royal-950 shadow-lift">
                    <p class="font-display text-4xl">{{ $s['stat_years'] }}+</p>
                    @if (site('about_badge_text'))<p class="text-sm font-medium">{{ site('about_badge_text') }}</p>@endif
                </div>
            @endif
        </div>
    </section>

    @if (site_shows('about_stats_show'))
        <section class="page pt-24">
            <dl class="grid gap-6 rounded-[2rem] bg-royal-950 p-10 text-white sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([[site('about_stat_listings'), number_format($totalListings)], [site('about_stat_areas'), $locationsCount], [site('about_stat_clients'), $s['stat_clients']], [site('about_stat_years'), $s['stat_years']]] as [$label, $value])
                    @continue($label === '' || (string) $value === '')
                    <div>
                        <dd class="font-display text-4xl text-gold-300">{{ $value }}</dd>
                        <dt class="mt-1 text-sm text-white/60">{{ $label }}</dt>
                    </div>
                @endforeach
            </dl>
        </section>
    @endif

    @if (site_shows('about_services_show'))
        <section class="page pt-24">
            @if (site('about_services_eyebrow'))<p class="eyebrow">{{ site('about_services_eyebrow') }}</p>@endif
            <h2 class="mt-2 font-display text-3xl text-royal-950 sm:text-4xl">{{ site('about_services_title') }}</h2>
            <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                {{-- Services with a blank heading are left out --}}
                @foreach (collect([1 => ['offer' => 'rent'], 2 => ['offer' => 'sale'], 3 => ['offer' => 'short_stay'], 4 => ['offer' => 'lease']])
                    ->map(fn ($query, $n) => [site("about_service_{$n}_title"), site("about_service_{$n}_text"), $query])
                    ->filter(fn ($card) => $card[0] !== '')->values() as $i => [$title, $text, $query])
                    <a href="{{ route('properties.index', $query) }}" class="card group p-7 transition hover:-translate-y-1 hover:shadow-lift" data-reveal style="--delay: {{ $i * 80 }}ms">
                        <p class="font-display text-3xl text-gold-500">0{{ $i + 1 }}</p>
                        <h3 class="mt-4 text-lg font-semibold text-royal-950">{{ $title }}</h3>
                        @if ($text)<p class="mt-2 text-sm leading-relaxed text-ink/60">{{ $text }}</p>@endif
                        @if (site('about_services_link'))<p class="mt-5 text-sm font-semibold text-royal-700 group-hover:text-gold-600">{{ site('about_services_link') }}</p>@endif
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @if (site_shows('about_reviews_show') && $testimonials->isNotEmpty())
        <section class="page pt-24">
            <div class="grid gap-6 md:grid-cols-3">
                @foreach ($testimonials as $t)
                    <figure class="card p-7">
                        <blockquote class="font-display text-lg leading-relaxed text-royal-950">“{{ $t->quote }}”</blockquote>
                        <figcaption class="mt-5 text-sm"><span class="font-semibold">{{ $t->name }}</span>@if ($t->role)<span class="text-ink/50"> · {{ $t->role }}</span>@endif</figcaption>
                    </figure>
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.site>
