<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title' => null, 'description' => null, 'image' => null, 'overlay' => false, 'actionBar' => false]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['title' => null, 'description' => null, 'image' => null, 'overlay' => false, 'actionBar' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
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
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <script>document.documentElement.classList.add('js')</script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($pageTitle); ?></title>
    <meta name="description" content="<?php echo e($description ?? $s['hero_text']); ?>">
    <meta property="og:title" content="<?php echo e($pageTitle); ?>">
    <meta property="og:description" content="<?php echo e($description ?? $s['hero_text']); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <?php if($image): ?><meta property="og:image" content="<?php echo e($image); ?>"><?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="#371a4c">
    <link rel="canonical" href="<?php echo e(url()->current()); ?>">
    <link rel="icon" href="<?php echo e(asset('favicon.svg')); ?>" type="image/svg+xml">
    <link rel="preload" href="<?php echo e(asset('fonts/inter-latin-wght-normal.woff2')); ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo e(asset('fonts/fraunces-latin-opsz-normal.woff2')); ?>" as="font" type="font/woff2" crossorigin>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo e($head ?? ''); ?>

</head>
<body class="min-h-screen <?php echo e($actionBar ? 'pb-20 lg:pb-0' : ''); ?>" x-data="{ menu: false, scrolled: window.scrollY > 24 }" @scroll.window="scrolled = window.scrollY > 24">
    <?php if (isset($component)) { $__componentOriginal13736734f571fcf84813ed9ba4ef844c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal13736734f571fcf84813ed9ba4ef844c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.preloader','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('preloader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal13736734f571fcf84813ed9ba4ef844c)): ?>
<?php $attributes = $__attributesOriginal13736734f571fcf84813ed9ba4ef844c; ?>
<?php unset($__attributesOriginal13736734f571fcf84813ed9ba4ef844c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal13736734f571fcf84813ed9ba4ef844c)): ?>
<?php $component = $__componentOriginal13736734f571fcf84813ed9ba4ef844c; ?>
<?php unset($__componentOriginal13736734f571fcf84813ed9ba4ef844c); ?>
<?php endif; ?>
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] btn-gold">Skip to content</a>

    
    <header class="fixed inset-x-0 top-0 z-50 transition duration-300 <?php echo e($tones['header']['class']); ?>" <?php echo $tones['header']['bind']; ?>>
        <div class="page flex h-18 items-center justify-between gap-6 py-3">
            <a href="<?php echo e(route('home')); ?>" class="shrink-0" aria-label="<?php echo e($s['business_name']); ?> home">
                <?php if($overlay): ?>
                    <?php if (isset($component)) { $__componentOriginal987d96ec78ed1cf75b349e2e5981978f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal987d96ec78ed1cf75b349e2e5981978f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logo','data' => ['light' => true,'xShow' => '!scrolled']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['light' => true,'x-show' => '!scrolled']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal987d96ec78ed1cf75b349e2e5981978f)): ?>
<?php $attributes = $__attributesOriginal987d96ec78ed1cf75b349e2e5981978f; ?>
<?php unset($__attributesOriginal987d96ec78ed1cf75b349e2e5981978f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal987d96ec78ed1cf75b349e2e5981978f)): ?>
<?php $component = $__componentOriginal987d96ec78ed1cf75b349e2e5981978f; ?>
<?php unset($__componentOriginal987d96ec78ed1cf75b349e2e5981978f); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal987d96ec78ed1cf75b349e2e5981978f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal987d96ec78ed1cf75b349e2e5981978f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logo','data' => ['xShow' => 'scrolled','xCloak' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-show' => 'scrolled','x-cloak' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal987d96ec78ed1cf75b349e2e5981978f)): ?>
<?php $attributes = $__attributesOriginal987d96ec78ed1cf75b349e2e5981978f; ?>
<?php unset($__attributesOriginal987d96ec78ed1cf75b349e2e5981978f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal987d96ec78ed1cf75b349e2e5981978f)): ?>
<?php $component = $__componentOriginal987d96ec78ed1cf75b349e2e5981978f; ?>
<?php unset($__componentOriginal987d96ec78ed1cf75b349e2e5981978f); ?>
<?php endif; ?>
                <?php else: ?>
                    <?php if (isset($component)) { $__componentOriginal987d96ec78ed1cf75b349e2e5981978f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal987d96ec78ed1cf75b349e2e5981978f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logo','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal987d96ec78ed1cf75b349e2e5981978f)): ?>
<?php $attributes = $__attributesOriginal987d96ec78ed1cf75b349e2e5981978f; ?>
<?php unset($__attributesOriginal987d96ec78ed1cf75b349e2e5981978f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal987d96ec78ed1cf75b349e2e5981978f)): ?>
<?php $component = $__componentOriginal987d96ec78ed1cf75b349e2e5981978f; ?>
<?php unset($__componentOriginal987d96ec78ed1cf75b349e2e5981978f); ?>
<?php endif; ?>
                <?php endif; ?>
            </a>

            <nav class="hidden items-center gap-1 lg:flex" aria-label="Main">
                <?php $__currentLoopData = $nav; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($item['href']); ?>"
                        class="rounded-full px-3.5 py-2 text-sm font-medium transition <?php echo e($item['active'] ? 'text-gold-600' : $tones['link']['class']); ?>"
                        <?php echo $item['active'] ? '' : $tones['link']['bind']; ?>><?php echo e($item['label']); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>

            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('properties.saved')); ?>" x-data :href="$store.saved.url"
                    class="relative grid size-10 place-items-center rounded-full transition <?php echo e($tones['icon']['class']); ?>" <?php echo $tones['icon']['bind']; ?>

                    aria-label="Saved properties">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'heart']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'heart']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                    <span x-show="$store.saved.count" x-text="$store.saved.count" x-cloak
                        class="absolute -right-0.5 -top-0.5 grid size-5 place-items-center rounded-full bg-gold-400 text-[10px] font-bold text-royal-950"></span>
                </a>
                <?php if(site('nav_cta')): ?><a href="<?php echo e(route('submit')); ?>" class="btn-gold hidden sm:inline-flex"><?php echo e(site('nav_cta')); ?></a><?php endif; ?>
                <button type="button" @click="menu = true" class="grid size-10 place-items-center rounded-full lg:hidden <?php echo e($tones['icon']['class']); ?>" <?php echo $tones['icon']['bind']; ?>

                    aria-label="Open menu">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'menu']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'menu']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                </button>
            </div>
        </div>
    </header>

    
    <div x-show="menu" x-cloak class="fixed inset-0 z-[60] lg:hidden" @keydown.escape.window="menu = false">
        <div x-show="menu" x-transition.opacity class="absolute inset-0 bg-royal-950/60 backdrop-blur-sm" @click="menu = false"></div>
        <div x-show="menu" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-x-full" x-transition:leave="transition duration-200 ease-in" x-transition:leave-end="translate-x-full"
            class="absolute inset-y-0 right-0 flex w-full max-w-sm flex-col bg-white p-6 shadow-lift">
            <div class="flex items-center justify-between">
                <?php if (isset($component)) { $__componentOriginal987d96ec78ed1cf75b349e2e5981978f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal987d96ec78ed1cf75b349e2e5981978f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logo','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal987d96ec78ed1cf75b349e2e5981978f)): ?>
<?php $attributes = $__attributesOriginal987d96ec78ed1cf75b349e2e5981978f; ?>
<?php unset($__attributesOriginal987d96ec78ed1cf75b349e2e5981978f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal987d96ec78ed1cf75b349e2e5981978f)): ?>
<?php $component = $__componentOriginal987d96ec78ed1cf75b349e2e5981978f; ?>
<?php unset($__componentOriginal987d96ec78ed1cf75b349e2e5981978f); ?>
<?php endif; ?>
                <button type="button" @click="menu = false" class="grid size-10 place-items-center rounded-full hover:bg-royal-50" aria-label="Close menu"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?></button>
            </div>
            <nav class="mt-8 flex flex-col">
                <?php $__currentLoopData = $nav; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($item['href']); ?>" class="rounded-2xl px-4 py-3 font-display text-xl <?php echo e($item['active'] ? 'bg-royal-50 text-royal-800' : 'text-ink hover:bg-royal-50'); ?>"><?php echo e($item['label']); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>
            <div class="mt-auto grid gap-3">
                <?php if(site('nav_cta')): ?><a href="<?php echo e(route('submit')); ?>" class="btn-gold w-full py-3"><?php echo e(site('nav_cta')); ?></a><?php endif; ?>
                <?php if($s['phone']): ?>
                    <a href="tel:<?php echo e(\App\Models\Setting::tel($s['phone'])); ?>" class="btn-ghost w-full py-3">Call <?php echo e($s['phone']); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <main id="main" class="<?php echo e($overlay ? '' : 'pt-18'); ?>">
        <?php echo e($slot); ?>

    </main>

    
    <footer class="relative mt-24 overflow-hidden bg-royal-950 text-white/70">
        <div class="pointer-events-none absolute -right-40 -top-40 size-[30rem] rounded-full bg-royal-700/30 blur-3xl"></div>

        
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
            
            <path d="M560 210 760 60l200 150" stroke="#dbab47" stroke-opacity=".12" stroke-width="18" stroke-linejoin="round"/>
            <path d="M660 190 760 115l70 52" stroke="#dbab47" stroke-opacity=".08" stroke-width="6" stroke-linejoin="round"/>
        </svg>
        <div class="page relative grid gap-12 py-16 md:grid-cols-2 lg:grid-cols-[1.4fr_1fr_1fr_1.2fr]">
            <div>
                <?php if (isset($component)) { $__componentOriginal987d96ec78ed1cf75b349e2e5981978f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal987d96ec78ed1cf75b349e2e5981978f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.logo','data' => ['light' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['light' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal987d96ec78ed1cf75b349e2e5981978f)): ?>
<?php $attributes = $__attributesOriginal987d96ec78ed1cf75b349e2e5981978f; ?>
<?php unset($__attributesOriginal987d96ec78ed1cf75b349e2e5981978f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal987d96ec78ed1cf75b349e2e5981978f)): ?>
<?php $component = $__componentOriginal987d96ec78ed1cf75b349e2e5981978f; ?>
<?php unset($__componentOriginal987d96ec78ed1cf75b349e2e5981978f); ?>
<?php endif; ?>
                <?php if(site('footer_text')): ?><p class="mt-5 max-w-sm text-sm leading-relaxed"><?php echo e(site('footer_text')); ?></p><?php endif; ?>
                <div class="mt-6 flex flex-wrap gap-2 text-sm">
                    <?php $__currentLoopData = ['facebook' => 'Facebook', 'instagram' => 'Instagram', 'tiktok' => 'TikTok', 'linkedin' => 'LinkedIn']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($s[$key]): ?>
                            <a href="<?php echo e($s[$key]); ?>" target="_blank" rel="noopener" class="rounded-full bg-white/5 px-3.5 py-1.5 hover:bg-white/10 hover:text-white"><?php echo e($label); ?></a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div>
                <p class="text-sm font-semibold text-gold-300"><?php echo e(site('footer_explore')); ?></p>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <?php $__currentLoopData = \App\Models\Property::OFFERS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><a href="<?php echo e(route('properties.index', ['offer' => $key])); ?>" class="hover:text-white"><?php echo e($label); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <li><a href="<?php echo e(route('properties.saved')); ?>" class="hover:text-white">Saved properties</a></li>
                </ul>
            </div>
            <div>
                <p class="text-sm font-semibold text-gold-300"><?php echo e(site('footer_company')); ?></p>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="<?php echo e(route('about')); ?>" class="hover:text-white">About us</a></li>
                    <li><a href="<?php echo e(route('blog')); ?>" class="hover:text-white">Journal</a></li>
                    <li><a href="<?php echo e(route('submit')); ?>" class="hover:text-white">List your property</a></li>
                    <?php if(site('footer_review_link')): ?><li><a href="<?php echo e(route('review')); ?>" class="hover:text-white"><?php echo e(site('footer_review_link')); ?></a></li><?php endif; ?>
                    <li><a href="<?php echo e(route('contact')); ?>" class="hover:text-white">Contact us</a></li>
                </ul>
            </div>
            <div>
                <p class="text-sm font-semibold text-gold-300"><?php echo e(site('footer_contact')); ?></p>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <?php if($s['phone']): ?><li><a href="tel:<?php echo e(\App\Models\Setting::tel($s['phone'])); ?>" class="hover:text-white"><?php echo e($s['phone']); ?></a></li><?php endif; ?>
                    <?php if($s['email']): ?><li><a href="mailto:<?php echo e($s['email']); ?>" class="hover:text-white"><?php echo e($s['email']); ?></a></li><?php endif; ?>
                    <?php if($s['address']): ?><li><?php echo e($s['address']); ?></li><?php endif; ?>
                    <?php if($s['hours']): ?><li><?php echo e($s['hours']); ?></li><?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="page relative flex flex-col gap-2 py-6 text-xs text-white/40 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; <?php echo e(date('Y')); ?> <?php echo e($s['business_name']); ?>. <?php echo e(site('footer_rights')); ?></p>
            <?php if(site('footer_note')): ?><p><?php echo e(site('footer_note')); ?></p><?php endif; ?>
        </div>
    </footer>

    
    <?php if($whatsapp): ?>
        <a href="<?php echo e($whatsapp); ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"
            class="fixed <?php echo e($actionBar ? 'bottom-24 lg:bottom-5' : 'bottom-5'); ?> right-5 z-40 grid size-14 place-items-center rounded-full bg-[#25d366] text-white shadow-lift transition hover:scale-105">
            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'whatsapp','class' => 'size-7']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'whatsapp','class' => 'size-7']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
        </a>
    <?php endif; ?>
</body>
</html>
<?php /**PATH C:\Users\princ\OneDrive\Desktop\Personal\luwoap-new\resources\views/components/layouts/site.blade.php ENDPATH**/ ?>