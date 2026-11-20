{{--
    Photo banner at the top of inner pages (everything except the home page).
    Use with <x-layouts.site overlay> so the header sits transparently over the photo.
    <x-page-hero eyebrow="Contact" title="Let's find your next place together.">Optional intro or extra content</x-page-hero>
--}}
@props(['title', 'eyebrow' => null])
<section {{ $attributes->merge(['class' => 'relative isolate overflow-hidden bg-royal-950 pb-12 pt-32 text-white sm:pb-16 sm:pt-40']) }}>
    <div class="absolute inset-0 -z-10" aria-hidden="true">
        <img src="{{ asset('images/page-hero.webp') }}"
            srcset="{{ asset('images/page-hero-900.webp') }} 900w, {{ asset('images/page-hero.webp') }} 1920w"
            sizes="100vw" width="1920" height="1080" alt="" fetchpriority="high" decoding="async"
            class="absolute inset-0 size-full object-cover object-[70%_45%] animate-hero-zoom">
        {{-- Shading keeps the heading readable on the left while the homes stay visible on the right --}}
        <div class="absolute inset-0 bg-gradient-to-r from-royal-950/90 via-royal-950/65 to-royal-950/20 max-sm:via-royal-950/75 max-sm:to-royal-950/55"></div>
        <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-b from-royal-950/70 to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-royal-950/80 to-transparent"></div>
        <div class="absolute -bottom-32 -left-24 size-[24rem] rounded-full bg-gold-500/15 blur-3xl"></div>
        <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-gold-400/60 to-transparent"></div>
    </div>

    <div class="page">
        <div class="animate-fade-up">
            @if ($eyebrow)<p class="eyebrow text-gold-300">{{ $eyebrow }}</p>@endif
            <h1 class="mt-3 max-w-3xl font-display text-4xl leading-tight sm:text-5xl">{{ $title }}</h1>
            {{ $slot }}
        </div>
    </div>
</section>
