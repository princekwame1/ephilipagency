<x-layouts.site overlay title="Saved properties">
    <x-page-hero :eyebrow="site('saved_eyebrow')" :title="site('saved_title')">
        @if (site('saved_text'))<p class="mt-4 max-w-2xl text-white/75">{{ site('saved_text') }}</p>@endif
    </x-page-hero>
    <section class="page pt-4" x-data x-init="
        {{-- Keep the URL in sync with what this browser has saved (e.g. when opened from the menu with an old list). --}}
        const want = $store.saved.ids.join(',');
        const have = new URLSearchParams(location.search).get('ids') || '';
        if (want !== have) location.replace($store.saved.url);
    ">

        @if ($properties->isEmpty())
            <div class="card mt-10 px-8 py-16 text-center">
                <p class="font-display text-2xl text-royal-950">{{ site('saved_empty_title') }}</p>
                @if (site('saved_empty_text'))<p class="mx-auto mt-2 max-w-md text-ink/60">{{ site('saved_empty_text') }}</p>@endif
                <a href="{{ route('properties.index') }}" class="btn-primary mt-6">Browse properties</a>
            </div>
        @else
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($properties as $property)
                    <div x-show="$store.saved.has({{ $property->id }})" x-transition>
                        <x-property-card :property="$property" />
                    </div>
                @endforeach
            </div>
            @if ($wa = \App\Models\Setting::whatsappUrl("Hello, I'd like to arrange viewings for these properties:\n".$properties->map(fn ($p) => '- '.$p->title.' ('.$p->reference.')')->implode("\n")))
                <div class="card mt-10 flex flex-wrap items-center justify-between gap-4 p-6">
                    <p class="text-ink/70">Want to see several of these in one trip? We can line up viewings back to back.</p>
                    <a href="{{ $wa }}" target="_blank" rel="noopener" class="btn-primary">Plan viewings on WhatsApp</a>
                </div>
            @endif
        @endif
    </section>
</x-layouts.site>
