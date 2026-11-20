<x-layouts.site overlay title="Journal" description="Guides and market news for renters, buyers and property owners in Ghana.">
    <x-page-hero :eyebrow="site('journal_eyebrow')" :title="site('journal_title')" />
    <section class="page pt-6">
        @if ($posts->isEmpty())
            <p class="mt-10 text-ink/60">{{ site('journal_empty') }}</p>
        @else
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    <x-post-card :post="$post" />
                @endforeach
            </div>
            <div class="mt-10">{{ $posts->links() }}</div>
        @endif
    </section>
</x-layouts.site>
