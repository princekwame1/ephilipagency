@props(['title' => null, 'description' => null, 'image' => null, 'overlay' => false, 'actionBar' => false])
@php
    $s = \App\Models\Setting::values();
    $pageTitle = $title ? $title.' | '.$s['business_name'] : $s['business_name'].' | '.$s['tagline'];
    $nav = [
        // Labels and on/off switches: Admin → Site content → Header & footer
        'rent' => ['href' => route('properties.index', ['offer' => 'rent']), 'active' => request('offer') === 'rent'],
        'buy' => ['href' => route('properties.index', ['offer' => 'sale']), 'active' => request('offer') === 'sale'],
        'short' => ['href' => route('properties.index', ['offer' => 'short_stay']), 'active' => request('offer') === 'short_stay'],
        'all' => ['href' => route('properties.index'), 'active' => request()->routeIs('properties.index') && ! request('offer')],
        'about' => ['href' => route('about'), 'active' => request()->routeIs('about')],
        'contact' => ['href' => route('contact'), 'active' => request()->routeIs('contact')],
    ];
    $nav = collect($nav)
        ->filter(fn ($item, $key) => site_shows("nav_{$key}_show") && site("nav_{$key}") !== '')
        ->map(fn ($item, $key) => $item + ['label' => site("nav_{$key}")])
        ->values()->all();
    // Header colours are rendered in the HTML for the first paint (no flash before scripts load),
    // then swapped by Alpine on scroll. Only the home page (overlay) starts transparent.
    $tone = fn (string $solid, string $clear) => $overlay
        ? ['class' => $clear, 'bind' => ':class="{ \''.$solid.'\': scrolled, \''.$clear.'\': !scrolled }"']
        : ['class' => $solid, 'bind' => ''];
    $tones = [
        'header' => $tone('bg-white/90 shadow-soft backdrop-blur-xl', 'bg-transparent'),
        'link' => $tone('text-ink/75 hover:text-royal-800', 'text-white/85 hover:text-white'),
        'icon' => $tone('text-royal-800 hover:bg-royal-50', 'text-white hover:bg-white/10'),
    ];
    $whatsapp = \App\Models\Setting::whatsappUrl('Hello '.$s['business_name'].', I would like some help finding a property.');
@endphp
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <script>document.documentElement.classList.add('js')</script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $description ?? $s['hero_text'] }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $description ?? $s['hero_text'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if ($image)<meta property="og:image" content="{{ $image }}">@endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="#371a4c">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preload" href="{{ asset('fonts/inter-latin-wght-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/fraunces-latin-opsz-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body class="min-h-screen {{ $actionBar ? 'pb-20 lg:pb-0' : '' }}" x-data="{ menu: false, scrolled: window.scrollY > 24 }" @scroll.window="scrolled = window.scrollY > 24">
    <x-preloader />
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] btn-gold">Skip to content</a>

    {{-- Header --}}
    <header class="fixed inset-x-0 top-0 z-50 transition duration-300 {{ $tones['header']['class'] }}" {!! $tones['header']['bind'] !!}>
        <div class="page flex h-18 items-center justify-between gap-6 py-3">
            <a href="{{ route('home') }}" class="shrink-0" aria-label="{{ $s['business_name'] }} home">
                @if ($overlay)
                    <x-logo light x-show="!scrolled" />
                    <x-logo x-show="scrolled" x-cloak />
                @else
                    <x-logo />
                @endif
            </a>

            <nav class="hidden items-center gap-1 lg:flex" aria-label="Main">
                @foreach ($nav as $item)
                    <a href="{{ $item['href'] }}"
                        class="rounded-full px-3.5 py-2 text-sm font-medium transition {{ $item['active'] ? 'text-gold-600' : $tones['link']['class'] }}"
                        {!! $item['active'] ? '' : $tones['link']['bind'] !!}>{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                <a href="{{ route('properties.saved') }}" x-data :href="$store.saved.url"
                    class="relative grid size-10 place-items-center rounded-full transition {{ $tones['icon']['class'] }}" {!! $tones['icon']['bind'] !!}
                    aria-label="Saved properties">
                    <x-icon name="heart" />
                    <span x-show="$store.saved.count" x-text="$store.saved.count" x-cloak
                        class="absolute -right-0.5 -top-0.5 grid size-5 place-items-center rounded-full bg-gold-400 text-[10px] font-bold text-royal-950"></span>
                </a>
                @if (site('nav_cta'))<a href="{{ route('submit') }}" class="btn-gold hidden sm:inline-flex">{{ site('nav_cta') }}</a>@endif
                <button type="button" @click="menu = true" class="grid size-10 place-items-center rounded-full lg:hidden {{ $tones['icon']['class'] }}" {!! $tones['icon']['bind'] !!}
                    aria-label="Open menu">
                    <x-icon name="menu" />
                </button>
            </div>
        </div>
    </header>

    {{-- Mobile menu --}}
    <div x-show="menu" x-cloak class="fixed inset-0 z-[60] lg:hidden" @keydown.escape.window="menu = false">
        <div x-show="menu" x-transition.opacity class="absolute inset-0 bg-royal-950/60 backdrop-blur-sm" @click="menu = false"></div>
        <div x-show="menu" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-x-full" x-transition:leave="transition duration-200 ease-in" x-transition:leave-end="translate-x-full"
            class="absolute inset-y-0 right-0 flex w-full max-w-sm flex-col bg-white p-6 shadow-lift">
            <div class="flex items-center justify-between">
                <x-logo />
                <button type="button" @click="menu = false" class="grid size-10 place-items-center rounded-full hover:bg-royal-50" aria-label="Close menu"><x-icon name="x" /></button>
            </div>
            <nav class="mt-8 flex flex-col">
                @foreach ($nav as $item)
                    <a href="{{ $item['href'] }}" class="rounded-2xl px-4 py-3 font-display text-xl {{ $item['active'] ? 'bg-royal-50 text-royal-800' : 'text-ink hover:bg-royal-50' }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>
            <div class="mt-auto grid gap-3">
                @if (site('nav_cta'))<a href="{{ route('submit') }}" class="btn-gold w-full py-3">{{ site('nav_cta') }}</a>@endif
                @if ($s['phone'])
                    <a href="tel:{{ \App\Models\Setting::tel($s['phone']) }}" class="btn-ghost w-full py-3">Call {{ $s['phone'] }}</a>
                @endif
            </div>
        </div>
    </div>

    <main id="main" class="{{ $overlay ? '' : 'pt-18' }}">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="relative mt-24 overflow-hidden bg-royal-950 text-white/70">
        <div class="pointer-events-none absolute -right-40 -top-40 size-[30rem] rounded-full bg-royal-700/30 blur-3xl"></div>

        {{-- Abstract pattern from the logo: a grid of gold rooflines, flowing swoosh curves and a gold top rule --}}
        <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-gold-400/60 to-transparent"></div>
        <svg class="pointer-events-none absolute inset-0 size-full text-gold-300 opacity-[0.09] [mask-image:radial-gradient(ellipse_80%_90%_at_85%_10%,#000_20%,transparent_75%)]" aria-hidden="true">
            <defs>
                <pattern id="footer-roofs" width="56" height="56" patternUnits="userSpaceOnUse">
                    <path d="M8 30 22 17l14 13" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                    <rect x="19.5" y="24" width="5" height="5" rx=".8" fill="none" stroke="currentColor" stroke-width="1"/>
                    <circle cx="46" cy="46" r="1.4" fill="currentColor"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#footer-roofs)"/>
        </svg>
        <svg class="pointer-events-none absolute -bottom-10 right-0 h-[26rem] w-[60rem] max-w-none opacity-40 sm:opacity-60" viewBox="0 0 960 416" fill="none" aria-hidden="true">
            <defs>
                <linearGradient id="footer-swoosh" x1="0" y1="0" x2="1" y2="0">
                    <stop offset="0" stop-color="#dbab47" stop-opacity="0"/>
                    <stop offset=".55" stop-color="#e5c06a" stop-opacity=".35"/>
                    <stop offset="1" stop-color="#f7eccf" stop-opacity=".1"/>
                </linearGradient>
            </defs>
            <g stroke="url(#footer-swoosh)" stroke-linecap="round">
                <path d="M0 330C180 250 380 230 560 262s300 40 400-20" stroke-width="2"/>
                <path d="M60 372C240 300 420 286 600 314s260 30 360-26" stroke-width="1.2"/>
                <path d="M120 410C300 352 470 342 640 366s230 22 320-20" stroke-width=".8"/>
            </g>
            {{-- large outlined roof echoing the logo --}}
            <path d="M560 210 760 60l200 150" stroke="#dbab47" stroke-opacity=".12" stroke-width="18" stroke-linejoin="round"/>
            <path d="M660 190 760 115l70 52" stroke="#dbab47" stroke-opacity=".08" stroke-width="6" stroke-linejoin="round"/>
        </svg>
        <div class="page relative grid gap-12 py-16 md:grid-cols-2 lg:grid-cols-[1.4fr_1fr_1fr_1.2fr]">
            <div>
                <x-logo light />
                @if (site('footer_text'))<p class="mt-5 max-w-sm text-sm leading-relaxed">{{ site('footer_text') }}</p>@endif
                <div class="mt-6 flex flex-wrap gap-2 text-sm">
                    @foreach (['facebook' => 'Facebook', 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'linkedin' => 'LinkedIn'] as $key => $label)
                        @if ($s[$key])
                            <a href="{{ $s[$key] }}" target="_blank" rel="noopener" class="rounded-full bg-white/5 px-3.5 py-1.5 hover:bg-white/10 hover:text-white">{{ $label }}</a>
                        @endif
                    @endforeach
                </div>
            </div>
            <div>
                <p class="text-sm font-semibold text-gold-300">{{ site('footer_explore') }}</p>
                <ul class="mt-4 space-y-2.5 text-sm">
                    @foreach (\App\Models\Property::OFFERS as $key => $label)
                        <li><a href="{{ route('properties.index', ['offer' => $key]) }}" class="hover:text-white">{{ $label }}</a></li>
                    @endforeach
                    <li><a href="{{ route('properties.saved') }}" class="hover:text-white">Saved properties</a></li>
                </ul>
            </div>
            <div>
                <p class="text-sm font-semibold text-gold-300">{{ site('footer_company') }}</p>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-white">About us</a></li>
                    <li><a href="{{ route('blog') }}" class="hover:text-white">Journal</a></li>
                    <li><a href="{{ route('submit') }}" class="hover:text-white">List your property</a></li>
                    @if (site('footer_review_link'))<li><a href="{{ route('review') }}" class="hover:text-white">{{ site('footer_review_link') }}</a></li>@endif
                    <li><a href="{{ route('contact') }}" class="hover:text-white">Contact us</a></li>
                </ul>
            </div>
            <div>
                <p class="text-sm font-semibold text-gold-300">{{ site('footer_contact') }}</p>
                <ul class="mt-4 space-y-2.5 text-sm">
                    @if ($s['phone'])<li><a href="tel:{{ \App\Models\Setting::tel($s['phone']) }}" class="hover:text-white">{{ $s['phone'] }}</a></li>@endif
                    @if ($s['email'])<li><a href="mailto:{{ $s['email'] }}" class="hover:text-white">{{ $s['email'] }}</a></li>@endif
                    @if ($s['address'])<li>{{ $s['address'] }}</li>@endif
                    @if ($s['hours'])<li>{{ $s['hours'] }}</li>@endif
                </ul>
            </div>
        </div>
        <div class="page relative flex flex-col gap-2 py-6 text-xs text-white/40 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} {{ $s['business_name'] }}. {{ site('footer_rights') }}</p>
            @if (site('footer_note'))<p>{{ site('footer_note') }}</p>@endif
        </div>
    </footer>

    {{-- WhatsApp shortcut --}}
    @if ($whatsapp)
        <a href="{{ $whatsapp }}" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"
            class="fixed {{ $actionBar ? 'bottom-24 lg:bottom-5' : 'bottom-5' }} right-5 z-40 grid size-14 place-items-center rounded-full bg-[#25d366] text-white shadow-lift transition hover:scale-105">
            <x-icon name="whatsapp" class="size-7" />
        </a>
    @endif
</body>
</html>
