@props(['post'])
<article {{ $attributes->merge(['class' => 'group relative overflow-hidden rounded-3xl bg-white shadow-soft transition hover:-translate-y-1 hover:shadow-lift']) }}>
    <div class="aspect-[16/10] overflow-hidden bg-royal-100">
        @if ($cover = $post->coverUrl())
            <img src="{{ $cover }}" alt="" loading="lazy" class="size-full object-cover transition duration-700 group-hover:scale-105">
        @else
            <div class="grid size-full place-items-center bg-gradient-to-br from-royal-800 to-royal-950 p-8">
                <p class="text-center font-display text-xl text-gold-300">{{ $post->title }}</p>
            </div>
        @endif
    </div>
    <div class="p-6">
        <p class="text-xs text-ink/50">{{ $post->published_at?->format('j M Y') }} · {{ $post->readingMinutes() }} min read</p>
        <h3 class="mt-2 font-display text-xl leading-snug text-royal-950">
            <a href="{{ route('blog.show', $post) }}" class="after:absolute after:inset-0 after:content-['']">{{ $post->title }}</a>
        </h3>
        @if ($post->excerpt)<p class="mt-2 line-clamp-2 text-sm text-ink/60">{{ $post->excerpt }}</p>@endif
    </div>
</article>
