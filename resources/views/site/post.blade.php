<x-layouts.site overlay :title="$post->title" :description="$post->excerpt" :image="$post->coverUrl()">
    <x-page-hero :title="$post->title">
        <x-slot:eyebrow><a href="{{ route('blog') }}" class="hover:text-white">Journal</a></x-slot:eyebrow>
        <p class="mt-4 text-sm text-white/65">{{ $post->published_at?->format('j F Y') }} · {{ $post->readingMinutes() }} min read</p>
    </x-page-hero>
    <article class="page max-w-3xl pt-12">
        @if ($cover = $post->coverUrl())
            <img src="{{ $cover }}" alt="" class="aspect-[16/9] w-full rounded-[2rem] object-cover shadow-soft">
        @endif
        @if ($post->excerpt)<p class="mt-8 text-xl leading-relaxed text-ink/75">{{ $post->excerpt }}</p>@endif
        <div class="prose-estate mt-8 text-base">{!! $post->html() !!}</div>

        <div class="mt-12 rounded-[2rem] bg-royal-950 p-8 text-white">
            <p class="font-display text-2xl">Ready to start looking?</p>
            <p class="mt-2 text-white/65">Browse verified homes or tell us what you need.</p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('properties.index') }}" class="btn-gold">Browse properties</a>
                <a href="{{ route('contact') }}" class="btn-light">Talk to an agent</a>
            </div>
        </div>
    </article>

    @if ($more->isNotEmpty())
        <section class="page pt-20">
            <h2 class="font-display text-3xl text-royal-950">More from the journal</h2>
            <div class="mt-8 grid gap-6 md:grid-cols-3">
                @foreach ($more as $item)
                    <x-post-card :post="$item" />
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.site>
