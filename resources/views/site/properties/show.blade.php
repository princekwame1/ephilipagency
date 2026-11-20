@php
    $images = $property->images;
    $count = max(1, $images->count());
    $agent = $property->agent;
    $s = \App\Models\Setting::values();
    $tab = old('type', session('tab', 'viewing'));
    $facts = array_filter([
        'Bedrooms' => $property->bedrooms ?: 'Studio',
        'Bathrooms' => $property->bathrooms ?: null,
        'Floor area' => $property->area ? number_format($property->area).' m²' : null,
        'Parking' => $property->parking ? $property->parking.' '.Str::plural('car', $property->parking) : null,
        'Furnishing' => $property->furnished ? 'Furnished' : 'Unfurnished',
        'Reference' => $property->reference,
    ]);
@endphp
<x-layouts.site action-bar :title="$property->title" :description="$property->summary ?: Str::limit(strip_tags($property->descriptionHtml()), 155)" :image="$property->coverUrl()">
    <x-slot:head>
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org', '@type' => 'Residence', 'name' => $property->title,
            'url' => route('properties.show', $property), 'image' => $images->map->url()->all(),
            'address' => ['@type' => 'PostalAddress', 'addressLocality' => $property->location, 'addressRegion' => $property->city, 'addressCountry' => 'GH'],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    </x-slot:head>

    <div class="page pt-8" x-data="gallery({{ $count }})" @keydown.window.escape="close()" @keydown.window.arrow-right="open && next()" @keydown.window.arrow-left="open && prev()">
        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-1.5 text-sm text-ink/50" aria-label="Breadcrumb">
            <a href="{{ route('properties.index') }}" class="hover:text-royal-700">Properties</a>
            <span>/</span>
            <a href="{{ route('properties.index', ['offer' => $property->offer_type]) }}" class="hover:text-royal-700">{{ $property->offerLabel() }}</a>
            @if ($property->category)
                <span>/</span>
                <a href="{{ route('properties.index', ['category' => $property->category->slug]) }}" class="hover:text-royal-700">{{ $property->category->name }}</a>
            @endif
        </nav>

        @if ($property->status !== 'published')
            <p class="mt-4 rounded-2xl bg-gold-100 px-5 py-3 text-sm font-medium text-gold-800">Draft preview. Only signed-in staff can see this page.</p>
        @endif

        {{-- Title --}}
        <div class="mt-4 flex flex-wrap items-end justify-between gap-6">
            <div class="max-w-3xl">
                <div class="flex flex-wrap gap-2">
                    <span class="chip">{{ $property->offerLabel() }}</span>
                    @if ($property->category)<span class="chip">{{ $property->category->name }}</span>@endif
                    @unless ($property->isAvailable())<span class="chip-gold">{{ $property->availabilityLabel() }}</span>@endunless
                </div>
                <h1 class="mt-3 font-display text-4xl leading-tight text-royal-950 sm:text-5xl">{{ $property->title }}</h1>
                <p class="mt-2 text-ink/60">{{ collect([$property->location, $property->city])->filter()->unique()->implode(', ') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" x-data @click="$store.saved.toggle({{ $property->id }})" class="btn-ghost"
                    :class="$store.saved.has({{ $property->id }}) && '!bg-gold-100 !text-gold-800'">
                    <x-icon name="heart" class="size-4" ::class="$store.saved.has({{ $property->id }}) && 'fill-current'" />
                    <span x-text="$store.saved.has({{ $property->id }}) ? 'Saved' : 'Save'">Save</span>
                </button>
                <button type="button" x-data="share(@js($property->title), @js(route('properties.show', $property)))" @click="go()" class="btn-ghost">
                    <x-icon name="share" class="size-4" /> <span x-text="copied ? 'Link copied' : 'Share'">Share</span>
                </button>
            </div>
        </div>

        {{-- Gallery mosaic --}}
        <div class="relative mt-6 grid h-[22rem] gap-2 overflow-hidden rounded-[2rem] sm:h-[30rem] sm:grid-cols-4 sm:grid-rows-2">
            @forelse ($images->take(5) as $i => $image)
                <button type="button" @click="show({{ $i }})"
                    class="group relative overflow-hidden bg-royal-100 {{ $i === 0 ? 'sm:col-span-2 sm:row-span-2' : 'hidden sm:block' }} {{ $images->count() < 5 && $i === 0 ? 'sm:col-span-4' : '' }}"
                    aria-label="Open photo {{ $i + 1 }} of {{ $images->count() }}">
                    <img src="{{ $image->url() }}" alt="{{ $property->title }} photo {{ $i + 1 }}" @if ($i > 0) loading="lazy" @endif class="size-full object-cover transition duration-700 group-hover:scale-105">
                </button>
            @empty
                <img src="{{ $property->coverUrl() }}" alt="" class="size-full object-cover sm:col-span-4 sm:row-span-2">
            @endforelse
            @if ($images->count() > 1)
                <button type="button" @click="show(0)" class="btn absolute bottom-4 right-4 bg-white/95 text-royal-900 shadow-soft hover:bg-white">
                    <x-icon name="expand" class="size-4" /> All {{ $images->count() }} photos
                </button>
            @endif
        </div>

        <div class="mt-10 grid gap-10 lg:grid-cols-[1fr_24rem]">
            <div class="min-w-0">
                {{-- Price + facts --}}
                <div class="card p-6 sm:p-8">
                    <div class="flex flex-wrap items-baseline justify-between gap-3">
                        <p class="font-display text-4xl text-royal-950">{{ $property->formattedPrice() }} <span class="text-lg text-ink/50">{{ $property->periodLabel() }}</span></p>
                        @if ($property->currency === 'USD' && (float) $property->price > 0)
                            <p class="text-sm text-ink/50">About GH₵ {{ number_format($property->priceInGhs()) }}</p>
                        @endif
                    </div>
                    <dl class="mt-6 grid grid-cols-2 gap-x-6 gap-y-5 sm:grid-cols-3">
                        @foreach ($facts as $label => $value)
                            <div>
                                <dt class="text-xs font-medium uppercase tracking-wider text-ink/45">{{ $label }}</dt>
                                <dd class="mt-1 text-base font-semibold text-ink">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                {{-- Description --}}
                @if ($property->description || $property->summary)
                    <section class="mt-10">
                        <h2 class="font-display text-2xl text-royal-950">{{ site('property_about_title') }}</h2>
                        <div class="rule-gold"></div>
                        @if ($property->summary)<p class="mt-6 text-lg leading-relaxed text-ink/80">{{ $property->summary }}</p>@endif
                        <div x-data="{ more: false }" class="relative mt-4">
                            <div class="prose-estate overflow-hidden" :class="!more && 'max-h-80'">{!! $property->descriptionHtml() !!}</div>
                            <div x-show="!more" class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-paper"></div>
                            <button type="button" x-show="!more" @click="more = true" x-init="$nextTick(() => { if ($el.previousElementSibling.previousElementSibling.scrollHeight <= 330) more = true })" class="relative mt-2 text-sm font-semibold text-royal-700 underline decoration-gold-400 underline-offset-4">Read more</button>
                        </div>
                    </section>
                @endif

                {{-- Amenities --}}
                @if ($property->amenities->isNotEmpty())
                    <section class="mt-10">
                        <h2 class="font-display text-2xl text-royal-950">{{ site('property_amenities_title') }}</h2>
                        <div class="rule-gold"></div>
                        <ul class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
                            @foreach ($property->amenities as $amenity)
                                <li class="flex items-center gap-3 rounded-2xl bg-white px-4 py-3 text-sm font-medium shadow-soft">
                                    <span class="size-1.5 shrink-0 rounded-full bg-gold-400"></span>{{ $amenity->name }}
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                {{-- Video tour --}}
                @php
                    $videoFile = $property->videoFileUrl();
                    $videoEmbed = $property->videoEmbedUrl();
                    $videoLink = ! $videoEmbed && preg_match('~^https?://~i', (string) $property->video_url) ? $property->video_url : null;
                @endphp
                @if ($videoFile || $videoEmbed || $videoLink)
                    <section class="mt-10">
                        <h2 class="font-display text-2xl text-royal-950">{{ site('property_video_title') }}</h2>
                        <div class="rule-gold"></div>
                        @if ($videoFile)
                            <video src="{{ $videoFile }}" controls playsinline preload="metadata" class="mt-6 aspect-video w-full rounded-3xl bg-royal-950 shadow-soft">
                                <a href="{{ $videoFile }}">Download the video tour</a>
                            </video>
                        @endif
                        @if ($videoEmbed)
                            <div class="mt-6 aspect-video overflow-hidden rounded-3xl bg-royal-950 shadow-soft">
                                <iframe src="{{ $videoEmbed }}" title="Video tour of {{ $property->title }}" class="size-full" loading="lazy" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        @elseif ($videoLink)
                            <a href="{{ $videoLink }}" target="_blank" rel="noopener nofollow" class="btn-ghost mt-6">Watch the video tour <x-icon name="external" class="size-3.5" /></a>
                        @endif
                    </section>
                @endif

                {{-- Location --}}
                <section class="mt-10">
                    <h2 class="font-display text-2xl text-royal-950">{{ site('property_location_title') }}</h2>
                    <div class="rule-gold"></div>
                    {{-- Locked map: only a blurred, pin-free view of the neighbourhood. The exact address
                         and directions are shared by the agent once a viewing is booked. --}}
                    <div class="relative isolate mt-6 aspect-[16/9] overflow-hidden rounded-3xl bg-royal-100 shadow-soft max-sm:aspect-[4/3]">
                        @if ($areaMap = $property->areaMapEmbedUrl())
                            <iframe src="{{ $areaMap }}" title="General area around {{ $property->location }}" tabindex="-1" aria-hidden="true" loading="lazy"
                                class="pointer-events-none absolute inset-0 -z-10 size-full scale-110 blur-[6px] saturate-50"></iframe>
                        @else
                            <svg class="absolute inset-0 -z-10 size-full text-royal-300/60" aria-hidden="true">
                                <defs><pattern id="map-grid" width="48" height="48" patternUnits="userSpaceOnUse"><path d="M0 24h48M24 0v48" stroke="currentColor" stroke-width="6"/><path d="M0 0l48 48" stroke="currentColor" stroke-width="2"/></pattern></defs>
                                <rect width="100%" height="100%" fill="url(#map-grid)" class="blur-[2px]"/>
                            </svg>
                        @endif
                        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-royal-950/80 via-royal-950/45 to-royal-900/25"></div>

                        <div class="flex size-full flex-col items-center justify-center px-6 text-center text-white">
                            <span class="grid size-14 place-items-center rounded-2xl bg-white/15 text-gold-300 ring-1 ring-white/25 backdrop-blur-md">
                                <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4.5" y="10.5" width="15" height="10" rx="2.5"/><path d="M8 10.5V7.5a4 4 0 0 1 8 0v3"/><circle cx="12" cy="15.5" r="1.3" fill="currentColor"/></svg>
                            </span>
                            <p class="mt-4 font-display text-2xl">{{ site('property_map_title') }}</p>
                            <p class="mt-2 max-w-sm text-sm text-white/75">This home is in <strong class="font-semibold text-white">{{ $property->location }}, {{ $property->city }}</strong>. {{ site('property_map_text') }}</p>
                            <a href="#enquire" @click="$dispatch('book-viewing')" class="btn-gold mt-5">{{ site('property_map_button') }}</a>
                        </div>
                    </div>
                </section>

                {{-- Mortgage calculator --}}
                @if ($property->offer_type === 'sale' && (float) $property->price > 0)
                    <section class="mt-10" x-data="mortgage({{ round($property->priceInGhs()) }})">
                        <h2 class="font-display text-2xl text-royal-950">{{ site('property_payment_title') }}</h2>
                        <div class="rule-gold"></div>
                        <div class="card mt-6 grid gap-8 p-6 sm:grid-cols-[1fr_auto] sm:p-8">
                            <div class="space-y-5">
                                <label class="block">
                                    <span class="flex justify-between text-sm"><span class="font-medium">Deposit</span><span class="text-ink/60" x-text="deposit + '%  (' + money(price * deposit / 100) + ')'"></span></span>
                                    <input type="range" min="0" max="80" step="5" x-model.number="deposit" class="mt-2 w-full accent-royal-700">
                                </label>
                                <label class="block">
                                    <span class="flex justify-between text-sm"><span class="font-medium">Interest rate</span><span class="text-ink/60" x-text="rate + '% a year'"></span></span>
                                    <input type="range" min="5" max="35" step="0.5" x-model.number="rate" class="mt-2 w-full accent-royal-700">
                                </label>
                                <label class="block">
                                    <span class="flex justify-between text-sm"><span class="font-medium">Term</span><span class="text-ink/60" x-text="years + ' years'"></span></span>
                                    <input type="range" min="1" max="30" step="1" x-model.number="years" class="mt-2 w-full accent-royal-700">
                                </label>
                            </div>
                            <div class="rounded-2xl bg-royal-950 p-6 text-white sm:w-60">
                                <p class="text-sm text-white/60">Estimated monthly</p>
                                <p class="mt-1 font-display text-3xl text-gold-300" x-text="money(monthly)"></p>
                                <p class="mt-4 text-xs text-white/50">Loan of <span x-text="money(loan)"></span>. A guide only; your bank will confirm the real figures.</p>
                            </div>
                        </div>
                    </section>
                @endif
            </div>

            {{-- Contact sidebar --}}
            <aside id="enquire" class="lg:sticky lg:top-24 lg:self-start">
                <div class="card p-6">
                    <div class="flex items-center gap-4">
                        @if ($agent?->photoUrl())
                            <img src="{{ $agent->photoUrl() }}" alt="" class="size-14 rounded-full object-cover">
                        @else
                            <span class="grid size-14 place-items-center rounded-full bg-royal-800 font-display text-lg text-gold-300">{{ $agent?->initials() ?? Str::substr($s['business_name'], 0, 1) }}</span>
                        @endif
                        <div>
                            <p class="font-semibold text-ink">{{ $agent?->name ?? $s['business_name'] }}</p>
                            <p class="text-sm text-ink/55">{{ $agent?->role ?? 'Lettings & sales team' }}</p>
                        </div>
                    </div>
                    <div class="mt-5 grid grid-cols-2 gap-2">
                        <a href="tel:{{ \App\Models\Setting::tel($property->contactPhone()) }}" class="btn-ghost"><x-icon name="phone" class="size-4" /> Call</a>
                        @if ($wa = $property->contactWhatsapp())
                            <a href="{{ $wa }}" target="_blank" rel="noopener" class="btn bg-[#25d366]/10 text-[#128c4a] hover:bg-[#25d366]/20"><x-icon name="whatsapp" class="size-4" /> WhatsApp</a>
                        @endif
                    </div>
                </div>

                <div class="card mt-4 p-6" x-data="{ tab: '{{ $tab }}' }" @book-viewing.window="tab = 'viewing'; $nextTick(() => $el.querySelector('#e-name')?.focus({ preventScroll: true }))">
                    @if (session('sent'))
                        <div class="rounded-2xl bg-royal-50 p-5 text-center">
                            <p class="font-display text-xl text-royal-950">{{ site('property_success_title') }}</p>
                            <p class="mt-2 text-sm text-ink/70">{{ session('sent') }}</p>
                        </div>
                    @else
                        <div class="grid grid-cols-2 rounded-full bg-royal-50 p-1 text-sm font-medium" role="tablist">
                            <button type="button" role="tab" @click="tab = 'viewing'" :aria-selected="tab === 'viewing'" class="rounded-full py-2 transition" :class="tab === 'viewing' ? 'bg-white text-royal-900 shadow-soft' : 'text-ink/55'">{{ site('property_tab_viewing') }}</button>
                            <button type="button" role="tab" @click="tab = 'enquiry'" :aria-selected="tab === 'enquiry'" class="rounded-full py-2 transition" :class="tab === 'enquiry' ? 'bg-white text-royal-900 shadow-soft' : 'text-ink/55'">{{ site('property_tab_question') }}</button>
                        </div>
                        <form method="post" action="{{ route('properties.enquire', $property) }}" class="mt-5 space-y-3">
                            @csrf
                            <input type="hidden" name="type" value="{{ $tab }}" :value="tab">
                            <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
                            <div>
                                <label class="label" for="e-name">Your name</label>
                                <input id="e-name" name="name" value="{{ old('name') }}" required class="input" autocomplete="name">
                                @error('name')<p class="error">{{ $message }}</p>@enderror
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="label" for="e-phone">Phone</label>
                                    <input id="e-phone" name="phone" type="tel" value="{{ old('phone') }}" required class="input" autocomplete="tel">
                                </div>
                                <div>
                                    <label class="label" for="e-email">Email <span class="font-normal text-ink/40">(optional)</span></label>
                                    <input id="e-email" name="email" type="email" value="{{ old('email') }}" class="input" autocomplete="email">
                                </div>
                            </div>
                            @error('phone')<p class="error">{{ $message }}</p>@enderror
                            @error('email')<p class="error">{{ $message }}</p>@enderror
                            <div x-show="tab === 'viewing'" class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="label" for="e-date">Day</label>
                                    <input id="e-date" name="preferred_date" type="date" min="{{ now()->toDateString() }}" value="{{ old('preferred_date') }}" class="input" :disabled="tab !== 'viewing'">
                                </div>
                                <div>
                                    <label class="label" for="e-time">Time</label>
                                    <select id="e-time" name="preferred_time" class="input" :disabled="tab !== 'viewing'">
                                        @foreach (\App\Models\Enquiry::TIMES as $key => $label)
                                            <option value="{{ $key }}" @selected(old('preferred_time') === $key)>{{ Str::before($label, ' (') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('preferred_date')<p class="error">{{ $message }}</p>@enderror
                            <div>
                                <label class="label" for="e-message">Message</label>
                                <textarea id="e-message" name="message" rows="3" class="input" :placeholder="tab === 'viewing' ? 'Anything we should know? (optional)' : 'What would you like to know?'">{{ old('message') }}</textarea>
                            </div>
                            <button class="btn-primary w-full py-3" x-text="tab === 'viewing' ? @js(site('property_viewing_button')) : @js(site('property_question_button'))">{{ site('property_viewing_button') }}</button>
                            @if (site('property_viewing_note'))<p x-show="tab === 'viewing'" class="text-center text-sm font-medium text-royal-800">{{ site('property_viewing_note') }}</p>@endif
                            <p class="text-center text-xs text-ink/45">We reply within a few hours, {{ Str::lower($s['hours'] ?: 'during office hours') }}.</p>
                        </form>
                    @endif
                </div>
            </aside>
        </div>

        {{-- Lightbox --}}
        <template x-teleport="body">
            <div x-show="open" x-cloak x-transition.opacity class="fixed inset-0 z-[80] flex flex-col bg-royal-950/95 backdrop-blur" role="dialog" aria-modal="true" aria-label="Photos"
                @touchstart="touchStart($event)" @touchend="touchEnd($event)">
                <div class="flex items-center justify-between p-4 text-white">
                    <p class="text-sm text-white/70"><span x-text="index + 1"></span> / {{ $images->count() }}</p>
                    <button type="button" @click="close()" class="grid size-11 place-items-center rounded-full bg-white/10 hover:bg-white/20" aria-label="Close"><x-icon name="x" /></button>
                </div>
                <div class="relative flex min-h-0 flex-1 items-center justify-center px-4 pb-4 sm:px-20">
                    @foreach ($images as $i => $image)
                        <img x-show="index === {{ $i }}" src="{{ $image->url() }}" alt="{{ $property->title }} photo {{ $i + 1 }}" loading="lazy" class="max-h-full max-w-full rounded-2xl object-contain">
                    @endforeach
                    <button type="button" @click="prev()" class="absolute left-4 top-1/2 hidden size-12 -translate-y-1/2 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20 sm:grid" aria-label="Previous photo"><x-icon name="chevron-left" /></button>
                    <button type="button" @click="next()" class="absolute right-4 top-1/2 hidden size-12 -translate-y-1/2 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20 sm:grid" aria-label="Next photo"><x-icon name="chevron-right" /></button>
                </div>
                <div class="flex justify-center gap-2 overflow-x-auto p-4 scrollbar-none">
                    @foreach ($images as $i => $image)
                        <button type="button" @click="index = {{ $i }}" class="h-14 w-20 shrink-0 overflow-hidden rounded-lg transition" :class="index === {{ $i }} ? 'ring-2 ring-gold-400' : 'opacity-50 hover:opacity-100'" aria-label="Photo {{ $i + 1 }}">
                            <img src="{{ $image->url() }}" alt="" loading="lazy" class="size-full object-cover">
                        </button>
                    @endforeach
                </div>
            </div>
        </template>
    </div>

    {{-- Similar --}}
    @if ($similar->isNotEmpty())
        <section class="page pt-20">
            <p class="eyebrow">{{ site('property_similar_eyebrow') }}</p>
            <h2 class="mt-2 font-display text-3xl text-royal-950">{{ site('property_similar_title') }}</h2>
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($similar as $item)
                    <x-property-card :property="$item" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- Mobile action bar --}}
    <div class="fixed inset-x-0 bottom-0 z-40 flex items-center gap-3 bg-white/95 px-5 py-3 shadow-[0_-8px_24px_-12px_rgb(31_11_46/0.25)] backdrop-blur lg:hidden">
        <div class="min-w-0 flex-1">
            <p class="truncate font-semibold text-royal-950">{{ $property->formattedPrice() }} <span class="text-sm font-normal text-ink/50">{{ $property->periodLabel() }}</span></p>
        </div>
        <a href="tel:{{ \App\Models\Setting::tel($property->contactPhone()) }}" class="btn-ghost" aria-label="Call"><x-icon name="phone" class="size-4" /></a>
        <a href="#enquire" @click="$dispatch('book-viewing')" class="btn-primary">{{ site('property_mobile_button') }}</a>
    </div>
</x-layouts.site>
