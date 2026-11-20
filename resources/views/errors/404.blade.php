<x-layouts.site title="Page not found">
    <section class="page py-24 text-center">
        <p class="font-display text-8xl text-gold-400">404</p>
        <h1 class="mt-4 font-display text-4xl text-royal-950">This door is locked.</h1>
        <p class="mx-auto mt-3 max-w-md text-ink/60">The page may have moved, or the listing has been let or sold. Have a look at what is available now.</p>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="{{ route('properties.index') }}" class="btn-primary">Browse properties</a>
            <a href="{{ route('home') }}" class="btn-ghost">Go home</a>
        </div>
    </section>
</x-layouts.site>
