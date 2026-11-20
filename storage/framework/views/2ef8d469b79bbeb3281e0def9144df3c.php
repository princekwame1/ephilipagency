<?php $s = \App\Models\Setting::values(); ?>

<?php if (isset($component)) { $__componentOriginalfefb4fd9b7004fa65f70c415ac76903e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfefb4fd9b7004fa65f70c415ac76903e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.site','data' => ['overlay' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.site'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['overlay' => true]); ?>
    
    <section class="relative isolate flex min-h-[92vh] items-end bg-royal-950 pb-16 pt-36 sm:items-center sm:pb-24">
        
        <div class="absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
            <?php if($s['hero_image_path']): ?>
                <img src="<?php echo e(\App\Models\Setting::heroImageUrl()); ?>" alt="" fetchpriority="high" class="absolute inset-0 size-full object-cover">
            <?php else: ?>
                
                <img src="<?php echo e(asset('images/hero-villa.webp')); ?>" srcset="<?php echo e(asset('images/hero-villa-900.webp')); ?> 900w, <?php echo e(asset('images/hero-villa.webp')); ?> 1536w"
                    sizes="100vw" width="1536" height="1024" alt="" fetchpriority="high" decoding="async"
                    class="absolute inset-0 size-full object-cover object-[68%_center] animate-hero-zoom">
            <?php endif; ?>
            
            <div class="absolute inset-0 bg-gradient-to-r from-royal-950/90 via-royal-950/55 to-royal-950/0 max-sm:from-royal-950/80 max-sm:via-royal-950/65 max-sm:to-royal-950/40"></div>
            <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-royal-950/70 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-royal-950/85 to-transparent"></div>
            <div class="absolute -bottom-32 -left-24 size-[28rem] rounded-full bg-gold-500/15 blur-3xl"></div>
        </div>

        <div class="page">
            <div class="max-w-3xl animate-fade-up">
                <?php if($s['tagline']): ?><p class="eyebrow text-gold-300"><?php echo e($s['tagline']); ?></p><?php endif; ?>
                <h1 class="mt-4 font-display text-5xl font-medium leading-[1.05] text-white sm:text-6xl lg:text-7xl"><?php echo e(site('hero_title')); ?></h1>
                <?php if(site('hero_text')): ?><p class="mt-6 max-w-xl text-lg leading-relaxed text-white/75"><?php echo e(site('hero_text')); ?></p><?php endif; ?>
            </div>

            
            <form action="<?php echo e(route('properties.index')); ?>" method="get" x-data="{ offer: '' }"
                class="mt-10 max-w-4xl animate-fade-up rounded-[2rem] bg-white p-3 shadow-lift [animation-delay:150ms]">
                <input type="hidden" name="offer" :value="offer" :disabled="!offer">
                <div class="flex gap-1 overflow-x-auto px-2 pb-3 pt-1 scrollbar-none" role="tablist">
                    <?php $__currentLoopData = ['' => 'All', 'rent' => 'Rent', 'sale' => 'Buy', 'short_stay' => 'Short stay', 'lease' => 'Lease']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button type="button" role="tab" @click="offer = '<?php echo e($key); ?>'" :aria-selected="offer === '<?php echo e($key); ?>'"
                            class="shrink-0 rounded-full px-4 py-2 text-sm font-medium transition"
                            :class="offer === '<?php echo e($key); ?>' ? 'bg-royal-800 text-white' : 'text-ink/60 hover:bg-royal-50'"><?php echo e($label); ?></button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="grid gap-2 sm:grid-cols-[1.5fr_1fr_1fr_auto]">
                    <label class="sr-only" for="hero-q">Search</label>
                    <div class="relative">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'search','class' => 'pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-royal-600']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-royal-600']); ?>
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
                        <input id="hero-q" name="q" type="search" class="input h-full pl-10" placeholder="<?php echo e(site('home_search_placeholder')); ?>">
                    </div>
                    <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['id' => 'hero-category','name' => 'category','label' => 'Type','placeholder' => 'Any type','icon' => 'home','options' => $categories->pluck('name', 'slug')->all()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'hero-category','name' => 'category','label' => 'Type','placeholder' => 'Any type','icon' => 'home','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories->pluck('name', 'slug')->all())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $attributes = $__attributesOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__attributesOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $component = $__componentOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__componentOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['id' => 'hero-beds','name' => 'beds','label' => 'Bedrooms','placeholder' => 'Any beds','icon' => 'bed','options' => collect([1, 2, 3, 4, 5])->mapWithKeys(fn ($n) => [$n => $n.'+ beds'])->all()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'hero-beds','name' => 'beds','label' => 'Bedrooms','placeholder' => 'Any beds','icon' => 'bed','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(collect([1, 2, 3, 4, 5])->mapWithKeys(fn ($n) => [$n => $n.'+ beds'])->all())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $attributes = $__attributesOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__attributesOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaled2cde6083938c436304f332ba96bb7c)): ?>
<?php $component = $__componentOriginaled2cde6083938c436304f332ba96bb7c; ?>
<?php unset($__componentOriginaled2cde6083938c436304f332ba96bb7c); ?>
<?php endif; ?>
                    <button class="btn-primary h-full px-7 py-3">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'search','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?> <?php echo e(site('home_search_button')); ?>

                    </button>
                </div>
            </form>

            <dl class="mt-12 flex flex-wrap gap-x-12 gap-y-6 text-white animate-fade-up [animation-delay:300ms]">
                <div><dt class="text-sm text-white/60"><?php echo e(site('stat_listings_label')); ?></dt><dd class="font-display text-3xl"><?php echo e(number_format($totalListings)); ?></dd></div>
                <?php if($s['stat_clients']): ?><div><dt class="text-sm text-white/60"><?php echo e(site('stat_clients_label')); ?></dt><dd class="font-display text-3xl"><?php echo e($s['stat_clients']); ?></dd></div><?php endif; ?>
                <?php if($s['stat_years']): ?><div><dt class="text-sm text-white/60"><?php echo e(site('stat_years_label')); ?></dt><dd class="font-display text-3xl"><?php echo e($s['stat_years']); ?></dd></div><?php endif; ?>
            </dl>
        </div>
    </section>

    
    <?php if(site_shows('home_categories_show')): ?>
        <section class="page pt-20">
            <div class="flex flex-wrap items-end justify-between gap-4" data-reveal>
                <div>
                    <?php if(site('home_categories_eyebrow')): ?><p class="eyebrow"><?php echo e(site('home_categories_eyebrow')); ?></p><?php endif; ?>
                    <h2 class="mt-2 font-display text-3xl text-royal-950 sm:text-4xl"><?php echo e(site('home_categories_title')); ?></h2>
                </div>
                <?php if(site('home_categories_button')): ?><a href="<?php echo e(route('properties.index')); ?>" class="btn-ghost"><?php echo e(site('home_categories_button')); ?> <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'arrow-right','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-right','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?></a><?php endif; ?>
            </div>
            <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('properties.index', ['category' => $category->slug])); ?>" data-reveal style="--delay: <?php echo e($i * 40); ?>ms"
                        class="rounded-2xl bg-white p-5 shadow-soft ring-1 ring-royal-100 transition hover:-translate-y-0.5 hover:ring-gold-300">
                        <p class="font-display text-lg text-royal-950"><?php echo e($category->name); ?></p>
                        <p class="mt-1 text-sm text-ink/50"><?php echo e($category->properties_count); ?> <?php echo e(Str::plural('listing', $category->properties_count)); ?></p>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if(site_shows('home_featured_show') && $featured->isNotEmpty()): ?>
        <section class="page pt-24">
            <div data-reveal>
                <?php if(site('home_featured_eyebrow')): ?><p class="eyebrow"><?php echo e(site('home_featured_eyebrow')); ?></p><?php endif; ?>
                <h2 class="mt-2 font-display text-3xl text-royal-950 sm:text-4xl"><?php echo e(site('home_featured_title')); ?></h2>
            </div>
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <?php $__currentLoopData = $featured; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginal58439408c0a13a2b2db6109a4cd58e23 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal58439408c0a13a2b2db6109a4cd58e23 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property-card','data' => ['property' => $property,'dataReveal' => true,'style' => '--delay: '.e(($i % 3) * 80).'ms']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['property' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($property),'data-reveal' => true,'style' => '--delay: '.e(($i % 3) * 80).'ms']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal58439408c0a13a2b2db6109a4cd58e23)): ?>
<?php $attributes = $__attributesOriginal58439408c0a13a2b2db6109a4cd58e23; ?>
<?php unset($__attributesOriginal58439408c0a13a2b2db6109a4cd58e23); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal58439408c0a13a2b2db6109a4cd58e23)): ?>
<?php $component = $__componentOriginal58439408c0a13a2b2db6109a4cd58e23; ?>
<?php unset($__componentOriginal58439408c0a13a2b2db6109a4cd58e23); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if(site_shows('home_why_show')): ?>
        <section class="relative isolate mt-24 overflow-hidden bg-royal-950 py-24 text-white sm:py-28">
            
            <?php if($s['why_image_path']): ?>
                <img src="<?php echo e(\App\Models\Setting::imageUrl('why_image_path')); ?>" alt="" loading="lazy" decoding="async" class="absolute inset-0 -z-20 size-full object-cover">
            <?php else: ?>
                <img src="<?php echo e(asset('images/why-estate.webp')); ?>" srcset="<?php echo e(asset('images/why-estate-900.webp')); ?> 900w, <?php echo e(asset('images/why-estate.webp')); ?> 1672w"
                    sizes="100vw" width="1672" height="941" alt="" loading="lazy" decoding="async"
                    class="absolute inset-0 -z-20 size-full object-cover object-[60%_center]">
            <?php endif; ?>
            <div class="absolute inset-0 -z-10 bg-gradient-to-r from-royal-950/95 via-royal-950/80 to-royal-950/55 max-lg:from-royal-950/75 max-lg:via-royal-950/60 max-lg:to-royal-950/60"></div>
            <div class="absolute inset-x-0 top-0 -z-10 h-32 bg-gradient-to-b from-royal-950/70 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 -z-10 h-32 bg-gradient-to-t from-royal-950/70 to-transparent"></div>

            <div class="page grid items-center gap-12 lg:grid-cols-[1fr_1.6fr]">
                <div data-reveal>
                    <?php if(site('home_why_eyebrow')): ?><p class="eyebrow text-gold-300"><?php echo e(site('home_why_eyebrow')); ?></p><?php endif; ?>
                    <h2 class="mt-3 font-display text-4xl leading-tight sm:text-5xl"><?php echo e(site('home_why_title')); ?></h2>
                    <?php if(site('home_why_text')): ?><p class="mt-5 text-white/75"><?php echo e(site('home_why_text')); ?></p><?php endif; ?>
                    <?php if(site('home_why_button')): ?><a href="<?php echo e(route('about')); ?>" class="btn-gold mt-8"><?php echo e(site('home_why_button')); ?></a><?php endif; ?>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    
                    <?php $__currentLoopData = collect(range(1, 4))->map(fn ($n) => [site("home_why_{$n}_title"), site("home_why_{$n}_text")])->filter(fn ($p) => $p[0] !== '')->values(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => [$heading, $text]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div data-reveal style="--delay: <?php echo e($i * 80); ?>ms"
                            class="rounded-3xl bg-royal-950/55 p-6 ring-1 ring-white/10 backdrop-blur-md transition duration-300 hover:-translate-y-1 hover:bg-royal-950/70 hover:ring-gold-400/40">
                            <p class="font-display text-4xl text-gold-400">0<?php echo e($i + 1); ?></p>
                            <h3 class="mt-3 text-lg font-semibold"><?php echo e($heading); ?></h3>
                            <?php if($text): ?><p class="mt-2 text-sm leading-relaxed text-white/70"><?php echo e($text); ?></p><?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if(site_shows('home_latest_show') && $latest->isNotEmpty()): ?>
        <section class="pt-24" x-data>
            <div class="page flex flex-wrap items-end justify-between gap-4" data-reveal>
                <div>
                    <?php if(site('home_latest_eyebrow')): ?><p class="eyebrow"><?php echo e(site('home_latest_eyebrow')); ?></p><?php endif; ?>
                    <h2 class="mt-2 font-display text-3xl text-royal-950 sm:text-4xl"><?php echo e(site('home_latest_title')); ?></h2>
                </div>
                <div class="flex gap-2">
                    <button type="button" class="grid size-11 place-items-center rounded-full bg-white shadow-soft hover:bg-royal-50" @click="$refs.track.scrollBy({ left: -340, behavior: 'smooth' })" aria-label="Scroll back"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'chevron-left']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-left']); ?>
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
                    <button type="button" class="grid size-11 place-items-center rounded-full bg-white shadow-soft hover:bg-royal-50" @click="$refs.track.scrollBy({ left: 340, behavior: 'smooth' })" aria-label="Scroll forward"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'chevron-right']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right']); ?>
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
            </div>
            <div x-ref="track" class="mt-8 flex snap-x snap-mandatory gap-6 overflow-x-auto scroll-px-5 px-5 pb-8 scrollbar-none sm:scroll-px-8 sm:px-8 xl:px-[max(2rem,calc((100vw-80rem)/2+2rem))]">
                <?php $__currentLoopData = $latest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginal58439408c0a13a2b2db6109a4cd58e23 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal58439408c0a13a2b2db6109a4cd58e23 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property-card','data' => ['property' => $property,'class' => 'w-[19rem] shrink-0 snap-start sm:w-[21rem]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['property' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($property),'class' => 'w-[19rem] shrink-0 snap-start sm:w-[21rem]']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal58439408c0a13a2b2db6109a4cd58e23)): ?>
<?php $attributes = $__attributesOriginal58439408c0a13a2b2db6109a4cd58e23; ?>
<?php unset($__attributesOriginal58439408c0a13a2b2db6109a4cd58e23); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal58439408c0a13a2b2db6109a4cd58e23)): ?>
<?php $component = $__componentOriginal58439408c0a13a2b2db6109a4cd58e23; ?>
<?php unset($__componentOriginal58439408c0a13a2b2db6109a4cd58e23); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if(site_shows('home_areas_show') && $locations->isNotEmpty()): ?>
        <section class="page pt-16">
            <div data-reveal>
                <?php if(site('home_areas_eyebrow')): ?><p class="eyebrow"><?php echo e(site('home_areas_eyebrow')); ?></p><?php endif; ?>
                <h2 class="mt-2 font-display text-3xl text-royal-950 sm:text-4xl"><?php echo e(site('home_areas_title')); ?></h2>
            </div>
            <div class="mt-8 grid grid-cols-2 gap-3 md:grid-cols-4">
                <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $loc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('properties.index', ['location' => $loc->location])); ?>" data-reveal style="--delay: <?php echo e($i * 40); ?>ms"
                        class="group flex items-center justify-between rounded-2xl bg-white px-5 py-4 shadow-soft transition hover:bg-gold-50">
                        <span>
                            <span class="block font-medium text-royal-950"><?php echo e($loc->location); ?></span>
                            <span class="text-sm text-ink/50"><?php echo e($loc->total); ?> <?php echo e(Str::plural('home', $loc->total)); ?></span>
                        </span>
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'arrow-right','class' => 'size-4 text-gold-500 transition group-hover:translate-x-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-right','class' => 'size-4 text-gold-500 transition group-hover:translate-x-1']); ?>
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
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if(site_shows('home_owners_show')): ?>
        <section class="page pt-24">
            <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-royal-800 via-royal-900 to-royal-950 px-8 py-14 text-white sm:px-14" data-reveal>
                <div class="absolute -right-20 -top-20 size-80 rounded-full bg-gold-400/25 blur-3xl"></div>
                <div class="relative grid items-center gap-8 lg:grid-cols-[1.5fr_1fr]">
                    <div>
                        <?php if(site('home_owners_eyebrow')): ?><p class="eyebrow text-gold-300"><?php echo e(site('home_owners_eyebrow')); ?></p><?php endif; ?>
                        <h2 class="mt-3 font-display text-3xl leading-tight sm:text-4xl"><?php echo e(site('home_owners_title')); ?></h2>
                        <?php if(site('home_owners_text')): ?><p class="mt-4 max-w-xl text-white/70"><?php echo e(site('home_owners_text')); ?></p><?php endif; ?>
                    </div>
                    <div class="flex flex-wrap gap-3 lg:justify-end">
                        <?php if(site('home_owners_button')): ?><a href="<?php echo e(route('submit')); ?>" class="btn-gold px-7 py-3.5"><?php echo e(site('home_owners_button')); ?></a><?php endif; ?>
                        <?php if(site('home_owners_button2')): ?><a href="<?php echo e(route('contact')); ?>" class="btn-light px-7 py-3.5"><?php echo e(site('home_owners_button2')); ?></a><?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    
    <?php if(site_shows('home_reviews_show') && $testimonials->isNotEmpty()): ?>
        <section class="page pt-24">
            <div class="text-center" data-reveal>
                <?php if(site('home_reviews_eyebrow')): ?><p class="eyebrow"><?php echo e(site('home_reviews_eyebrow')); ?></p><?php endif; ?>
                <h2 class="mt-2 font-display text-3xl text-royal-950 sm:text-4xl"><?php echo e(site('home_reviews_title')); ?></h2>
            </div>
            <div class="mt-10 grid gap-6 md:grid-cols-3">
                <?php $__currentLoopData = $testimonials->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <figure class="card flex flex-col p-7" data-reveal style="--delay: <?php echo e($i * 80); ?>ms">
                        <p class="text-gold-500" aria-label="<?php echo e($t->rating); ?> out of 5"><?php echo e(str_repeat('★', $t->rating)); ?><span class="text-royal-100"><?php echo e(str_repeat('★', 5 - $t->rating)); ?></span></p>
                        <blockquote class="mt-4 flex-1 font-display text-lg leading-relaxed text-royal-950">“<?php echo e($t->quote); ?>”</blockquote>
                        <figcaption class="mt-6 text-sm"><span class="font-semibold text-ink"><?php echo e($t->name); ?></span><?php if($t->role): ?><span class="text-ink/50"> · <?php echo e($t->role); ?></span><?php endif; ?></figcaption>
                    </figure>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php if(site('home_reviews_button')): ?>
                <p class="mt-8 text-center"><a href="<?php echo e(route('review')); ?>" class="btn-ghost"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'star','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'star','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?> <?php echo e(site('home_reviews_button')); ?></a></p>
            <?php endif; ?>
        </section>
    <?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfefb4fd9b7004fa65f70c415ac76903e)): ?>
<?php $attributes = $__attributesOriginalfefb4fd9b7004fa65f70c415ac76903e; ?>
<?php unset($__attributesOriginalfefb4fd9b7004fa65f70c415ac76903e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfefb4fd9b7004fa65f70c415ac76903e)): ?>
<?php $component = $__componentOriginalfefb4fd9b7004fa65f70c415ac76903e; ?>
<?php unset($__componentOriginalfefb4fd9b7004fa65f70c415ac76903e); ?>
<?php endif; ?>
<?php /**PATH C:\Users\princ\OneDrive\Desktop\Personal\luwoap-new\resources\views/site/home.blade.php ENDPATH**/ ?>