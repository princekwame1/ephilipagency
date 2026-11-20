@php
    $offerTitle = isset($filters['offer']) ? \App\Models\Property::OFFERS[$filters['offer']] : null;
    $heading = trim(($category?->name ? Str::plural($category->name) : 'Properties').' '.($offerTitle ? strtolower($offerTitle) : '').(isset($filters['location']) ? ' in '.$filters['location'] : ''));
    $view = $filters['view'] ?? 'grid';
    $chips = [];
    $labels = ['q' => fn ($v) => '“'.$v.'”', 'offer' => fn ($v) => \App\Models\Property::OFFERS[$v] ?? $v, 'category' => fn ($v) => $category?->name ?? $v,
        'location' => fn ($v) => $v, 'beds' => fn ($v) => $v.'+ beds', 'baths' => fn ($v) => $v.'+ baths', 'min' => fn ($v) => 'From GH₵ '.number_format($v),
        'max' => fn ($v) => 'Up to GH₵ '.number_format($v), 'furnished' => fn ($v) => 'Furnished'];
    foreach ($labels as $key => $fmt) {
        if (filled($filters[$key] ?? null)) {
            $chips[] = ['label' => $fmt($filters[$key]), 'url' => request()->fullUrlWithoutQuery([$key, 'page'])];
        }
    }
    foreach ($filters['amenities'] ?? [] as $id) {
        if ($a = $amenities->firstWhere('id', (int) $id)) {
            $rest = array_values(array_diff($filters['amenities'], [$id]));
            $chips[] = ['label' => $a->name, 'url' => route('properties.index', array_merge(request()->except(['amenities', 'page']), $rest ? ['amenities' => $rest] : []))];
        }
    }
@endphp
<x-layouts.site overlay :title="$heading" :description="'Browse '.strtolower($heading).' across Ghana. Verified listings with photos, prices and viewing booking.'">
    <x-page-hero :eyebrow="number_format($properties->total()).' '.Str::plural('result', $properties->total())" :title="$heading">
        {{-- Quick offer tabs --}}
        <div class="mt-6 flex gap-2 overflow-x-auto scrollbar-none">
            @foreach (['' => 'Everything'] + \App\Models\Property::OFFERS as $key => $label)
                @php($active = ($filters['offer'] ?? '') === $key)
                <a href="{{ route('properties.index', array_filter(array_merge(request()->except(['offer', 'page']), ['offer' => $key ?: null]))) }}"
                    class="shrink-0 rounded-full px-4 py-2 text-sm font-medium backdrop-blur-sm transition {{ $active ? 'bg-gold-400 text-royal-950' : 'bg-white/10 text-white/85 hover:bg-white/20' }}">{{ $label }}</a>
            @endforeach
        </div>
    </x-page-hero>

    <div class="page grid gap-8 pt-8 lg:grid-cols-[18rem_1fr]" x-data="{ filters: false }">
        {{-- Filters --}}
        <aside>
            <div x-show="filters" x-cloak x-transition.opacity class="fixed inset-0 z-[60] bg-royal-950/50 lg:hidden" @click="filters = false"></div>
            <form method="get" action="{{ route('properties.index') }}"
                class="fixed inset-y-0 left-0 z-[61] w-[88%] max-w-sm -translate-x-full overflow-y-auto bg-white p-6 shadow-lift transition-transform duration-300 lg:sticky lg:top-24 lg:z-auto lg:overflow-visible lg:w-auto lg:max-w-none lg:translate-x-0 lg:rounded-3xl lg:shadow-soft"
                :class="filters && '!translate-x-0'">
                <div class="flex items-center justify-between">
                    <p class="font-display text-xl text-royal-950">Filters</p>
                    <button type="button" class="grid size-9 place-items-center rounded-full hover:bg-royal-50 lg:hidden" @click="filters = false" aria-label="Close filters"><x-icon name="x" /></button>
                </div>
                @foreach (['sort', 'view'] as $keep)
                    @if (filled($filters[$keep] ?? null))<input type="hidden" name="{{ $keep }}" value="{{ $filters[$keep] }}">@endif
                @endforeach

                <div class="mt-5 space-y-4">
                    <div>
                        <label class="label" for="f-q">Keyword</label>
                        <input id="f-q" name="q" value="{{ $filters['q'] ?? '' }}" class="input" placeholder="Area, name or ref">
                    </div>
                    <div>
                        <label class="label" for="f-offer">Looking to</label>
                        <x-select id="f-offer" name="offer" label="Looking to" placeholder="Any" icon="key"
                            :options="\App\Models\Property::OFFERS" :value="$filters['offer'] ?? ''" />
                    </div>
                    <div>
                        <label class="label" for="f-category">Property type</label>
                        <x-select id="f-category" name="category" label="Property type" placeholder="Any type" icon="home"
                            :options="$categories->pluck('name', 'slug')->all()" :value="$filters['category'] ?? ''" />
                    </div>
                    <div>
                        <label class="label" for="f-location">Location</label>
                        <x-select id="f-location" name="location" label="Location" placeholder="Anywhere" icon="map-pin"
                            :options="$locations->combine($locations)->all()" :value="$filters['location'] ?? ''" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="label" for="f-beds">Beds</label>
                            <x-select id="f-beds" name="beds" label="Beds" placeholder="Any" icon="bed"
                                :options="collect([1, 2, 3, 4, 5])->mapWithKeys(fn ($n) => [$n => $n.'+'])->all()" :value="$filters['beds'] ?? ''" />
                        </div>
                        <div>
                            <label class="label" for="f-baths">Baths</label>
                            <x-select id="f-baths" name="baths" label="Baths" placeholder="Any" icon="bath"
                                :options="collect([1, 2, 3, 4])->mapWithKeys(fn ($n) => [$n => $n.'+'])->all()" :value="$filters['baths'] ?? ''" />
                        </div>
                    </div>
                    <div>
                        <span class="label">Price (GH₵)</span>
                        <div class="grid grid-cols-2 gap-3">
                            <input name="min" type="number" min="0" step="any" inputmode="numeric" value="{{ $filters['min'] ?? '' }}" class="input" placeholder="Min" aria-label="Minimum price">
                            <input name="max" type="number" min="0" step="any" inputmode="numeric" value="{{ $filters['max'] ?? '' }}" class="input" placeholder="Max" aria-label="Maximum price">
                        </div>
                        <p class="mt-1.5 text-xs text-ink/45">US$ listings are converted at GH₵ {{ \App\Models\Setting::get('usd_rate') }}.</p>
                    </div>
                    <label class="flex cursor-pointer items-center gap-3 text-sm">
                        <input type="checkbox" name="furnished" value="1" @checked(! empty($filters['furnished'])) class="size-4 rounded accent-royal-700"> Furnished only
                    </label>
                    @if ($amenities->isNotEmpty())
                        <div x-data="{ more: {{ count($filters['amenities'] ?? []) ? 'true' : 'false' }} }">
                            <span class="label">Amenities</span>
                            <div class="grid grid-cols-2 gap-x-3 gap-y-2 text-sm">
                                @foreach ($amenities as $i => $a)
                                    <label class="flex cursor-pointer items-center gap-2" @if ($i >= 6) x-show="more" x-cloak @endif>
                                        <input type="checkbox" name="amenities[]" value="{{ $a->id }}" @checked(in_array($a->id, array_map('intval', $filters['amenities'] ?? []), true)) class="size-4 rounded accent-royal-700"> {{ $a->name }}
                                    </label>
                                @endforeach
                            </div>
                            @if ($amenities->count() > 6)
                                <button type="button" class="mt-2 text-xs font-semibold text-royal-700" @click="more = !more" x-text="more ? 'Show fewer' : 'Show all {{ $amenities->count() }}'"></button>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="mt-6 grid gap-2">
                    <button class="btn-primary w-full py-3">Show results</button>
                    @if ($activeFilters)<a href="{{ route('properties.index') }}" class="btn-ghost w-full">Clear all</a>@endif
                </div>
            </form>
        </aside>

        {{-- Results --}}
        <div>
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" class="btn-ghost lg:hidden" @click="filters = true"><x-icon name="sliders" class="size-4" /> Filters @if ($activeFilters)<span class="grid size-5 place-items-center rounded-full bg-gold-400 text-[10px] text-royal-950">{{ $activeFilters }}</span>@endif</button>
                <form method="get" class="ml-auto flex items-center gap-2">
                    @foreach (request()->except(['sort', 'page']) as $key => $value)
                        @if (is_array($value))
                            @foreach ($value as $v)<input type="hidden" name="{{ $key }}[]" value="{{ $v }}">@endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <label for="sort" class="sr-only">Sort</label>
                    <select id="sort" name="sort" class="input w-auto bg-white py-2.5 shadow-soft" data-autosubmit>
                        @foreach (\App\Models\Property::SORTS as $key => $label)
                            <option value="{{ $key }}" @selected(($filters['sort'] ?? 'newest') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
                <div class="flex rounded-full bg-white p-1 shadow-soft">
                    @foreach (['grid' => 'Grid view', 'list' => 'List view'] as $mode => $label)
                        <a href="{{ request()->fullUrlWithQuery(['view' => $mode]) }}" aria-label="{{ $label }}"
                            class="grid size-9 place-items-center rounded-full transition {{ $view === $mode ? 'bg-royal-800 text-white' : 'text-ink/50 hover:text-royal-800' }}"><x-icon :name="$mode" class="size-4" /></a>
                    @endforeach
                </div>
            </div>

            @if ($chips)
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($chips as $chip)
                        <a href="{{ $chip['url'] }}" class="chip gap-1.5 hover:bg-royal-100">{{ $chip['label'] }} <x-icon name="x" class="size-3.5" /><span class="sr-only">Remove filter</span></a>
                    @endforeach
                </div>
            @endif

            @if ($properties->isEmpty())
                <div class="card mt-6 px-8 py-16 text-center">
                    <p class="font-display text-2xl text-royal-950">Nothing matches yet</p>
                    <p class="mx-auto mt-2 max-w-md text-ink/60">Try fewer filters, or tell us what you need and we will look for you. New homes come in every week.</p>
                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                        <a href="{{ route('properties.index') }}" class="btn-ghost">Clear filters</a>
                        <a href="{{ route('contact') }}" class="btn-primary">Ask an agent</a>
                    </div>
                </div>
            @else
                <div class="mt-6 grid gap-6 {{ $view === 'list' ? '' : 'sm:grid-cols-2 xl:grid-cols-3' }}">
                    @foreach ($properties as $property)
                        <x-property-card :property="$property" :layout="$view" />
                    @endforeach
                </div>
                <div class="mt-10">{{ $properties->onEachSide(1)->links() }}</div>
            @endif
        </div>
    </div>
</x-layouts.site>
