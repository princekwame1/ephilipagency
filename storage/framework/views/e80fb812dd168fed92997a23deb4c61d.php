<?php ($s = \App\Models\Setting::values()); ?>
<?php if (isset($component)) { $__componentOriginalfefb4fd9b7004fa65f70c415ac76903e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfefb4fd9b7004fa65f70c415ac76903e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.site','data' => ['overlay' => true,'title' => 'About us']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.site'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['overlay' => true,'title' => 'About us']); ?>
    
    <?php if (isset($component)) { $__componentOriginala9d931d4f11b4d2850df99e991db1dca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala9d931d4f11b4d2850df99e991db1dca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-hero','data' => ['eyebrow' => site('about_eyebrow'),'title' => site('about_title')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(site('about_eyebrow')),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(site('about_title'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala9d931d4f11b4d2850df99e991db1dca)): ?>
<?php $attributes = $__attributesOriginala9d931d4f11b4d2850df99e991db1dca; ?>
<?php unset($__attributesOriginala9d931d4f11b4d2850df99e991db1dca); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala9d931d4f11b4d2850df99e991db1dca)): ?>
<?php $component = $__componentOriginala9d931d4f11b4d2850df99e991db1dca; ?>
<?php unset($__componentOriginala9d931d4f11b4d2850df99e991db1dca); ?>
<?php endif; ?>
    <section class="page grid gap-12 pt-16 lg:grid-cols-[1.2fr_1fr] lg:items-center">
        <div data-reveal>
            <div class="prose-estate text-base">
                <?php $__currentLoopData = array_filter(preg_split("/\n\s*\n/", trim(site('about')))); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $para): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p><?php echo e($para); ?></p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <div class="relative" data-reveal style="--delay: 120ms">
            <div class="aspect-[4/5] overflow-hidden rounded-[2.5rem] bg-royal-900 shadow-lift">
                <img src="<?php echo e(\App\Models\Setting::imageUrl('about_image_path')); ?>" alt="" class="size-full object-cover opacity-90">
            </div>
            <?php if($s['stat_years']): ?>
                <div class="absolute -bottom-6 -left-6 rounded-3xl bg-gold-400 px-7 py-5 text-royal-950 shadow-lift">
                    <p class="font-display text-4xl"><?php echo e($s['stat_years']); ?>+</p>
                    <?php if(site('about_badge_text')): ?><p class="text-sm font-medium"><?php echo e(site('about_badge_text')); ?></p><?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php if(site_shows('about_stats_show')): ?>
        <section class="page pt-24">
            <dl class="grid gap-6 rounded-[2rem] bg-royal-950 p-10 text-white sm:grid-cols-2 lg:grid-cols-4">
                <?php $__currentLoopData = [[site('about_stat_listings'), number_format($totalListings)], [site('about_stat_areas'), $locationsCount], [site('about_stat_clients'), $s['stat_clients']], [site('about_stat_years'), $s['stat_years']]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($label === '' || (string) $value === '') continue; ?>
                    <div>
                        <dd class="font-display text-4xl text-gold-300"><?php echo e($value); ?></dd>
                        <dt class="mt-1 text-sm text-white/60"><?php echo e($label); ?></dt>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </dl>
        </section>
    <?php endif; ?>

    <?php if(site_shows('about_services_show')): ?>
        <section class="page pt-24">
            <?php if(site('about_services_eyebrow')): ?><p class="eyebrow"><?php echo e(site('about_services_eyebrow')); ?></p><?php endif; ?>
            <h2 class="mt-2 font-display text-3xl text-royal-950 sm:text-4xl"><?php echo e(site('about_services_title')); ?></h2>
            <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                
                <?php $__currentLoopData = collect([1 => ['offer' => 'rent'], 2 => ['offer' => 'sale'], 3 => ['offer' => 'short_stay'], 4 => ['offer' => 'lease']])
                    ->map(fn ($query, $n) => [site("about_service_{$n}_title"), site("about_service_{$n}_text"), $query])
                    ->filter(fn ($card) => $card[0] !== '')->values(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => [$title, $text, $query]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('properties.index', $query)); ?>" class="card group p-7 transition hover:-translate-y-1 hover:shadow-lift" data-reveal style="--delay: <?php echo e($i * 80); ?>ms">
                        <p class="font-display text-3xl text-gold-500">0<?php echo e($i + 1); ?></p>
                        <h3 class="mt-4 text-lg font-semibold text-royal-950"><?php echo e($title); ?></h3>
                        <?php if($text): ?><p class="mt-2 text-sm leading-relaxed text-ink/60"><?php echo e($text); ?></p><?php endif; ?>
                        <?php if(site('about_services_link')): ?><p class="mt-5 text-sm font-semibold text-royal-700 group-hover:text-gold-600"><?php echo e(site('about_services_link')); ?></p><?php endif; ?>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if(site_shows('about_reviews_show') && $testimonials->isNotEmpty()): ?>
        <section class="page pt-24">
            <div class="grid gap-6 md:grid-cols-3">
                <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <figure class="card p-7">
                        <blockquote class="font-display text-lg leading-relaxed text-royal-950">“<?php echo e($t->quote); ?>”</blockquote>
                        <figcaption class="mt-5 text-sm"><span class="font-semibold"><?php echo e($t->name); ?></span><?php if($t->role): ?><span class="text-ink/50"> · <?php echo e($t->role); ?></span><?php endif; ?></figcaption>
                    </figure>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
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
<?php /**PATH C:\Users\princ\OneDrive\Desktop\Personal\luwoap-new\resources\views/site/about.blade.php ENDPATH**/ ?>