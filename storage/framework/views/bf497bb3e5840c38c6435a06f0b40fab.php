<?php
    $offerTitle = isset($filters['offer']) ? \App\Models\Property::OFFERS[$filters['offer']] : null;
    $heading = trim(($category?->name ? Str::plural($category->name) : 'Properties').' '.($offerTitle ? strtolower($offerTitle) : '').(isset($filters['location']) ? ' in '.$filters['location'] : ''));
    $view = $filters['view'] ?? 'grid';
    $chips = [];
    $labels = ['q' => fn ($v) => '“'.$v.'”', 'offer' => fn ($v) => \App\Models\Property::OFFERS[$v] ?? $v, 'category' => fn ($v) => $category?->name ?? $v,
        'location' => fn ($v) => $v, 'beds' => fn ($v) => $v.'+ beds', 'baths' => fn ($v) => $v.'+ baths', 'min' => fn ($v) => 'From GH₵ '.number_format($v),
        'max' => fn ($v) => 'Up to GH₵ '.number_format($v), 'furnished' => fn ($v) => 'Furnished'];
    foreach ($labels as $key => $fmt) {
        if (filled($filters[$key] ?? null)) {
            $chips[] = ['label' => $fmt($filters[$key]), 'url' => request()->fullUrlWithoutQuery([$key, 'page'])];
        }
    }
    foreach ($filters['amenities'] ?? [] as $id) {
        if ($a = $amenities->firstWhere('id', (int) $id)) {
            $rest = array_values(array_diff($filters['amenities'], [$id]));
            $chips[] = ['label' => $a->name, 'url' => route('properties.index', array_merge(request()->except(['amenities', 'page']), $rest ? ['amenities' => $rest] : []))];
        }
    }
?>
<?php if (isset($component)) { $__componentOriginalfefb4fd9b7004fa65f70c415ac76903e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfefb4fd9b7004fa65f70c415ac76903e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.site','data' => ['overlay' => true,'title' => $heading,'description' => 'Browse '.strtolower($heading).' across Ghana. Verified listings with photos, prices and viewing booking.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.site'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['overlay' => true,'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($heading),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Browse '.strtolower($heading).' across Ghana. Verified listings with photos, prices and viewing booking.')]); ?>
    <?php if (isset($component)) { $__componentOriginala9d931d4f11b4d2850df99e991db1dca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala9d931d4f11b4d2850df99e991db1dca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-hero','data' => ['eyebrow' => number_format($properties->total()).' '.Str::plural('result', $properties->total()),'title' => $heading]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($properties->total()).' '.Str::plural('result', $properties->total())),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($heading)]); ?>
        
        <div class="mt-6 flex gap-2 overflow-x-auto scrollbar-none">
            <?php $__currentLoopData = ['' => 'Everything'] + \App\Models\Property::OFFERS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php ($active = ($filters['offer'] ?? '') === $key); ?>
                <a href="<?php echo e(route('properties.index', array_filter(array_merge(request()->except(['offer', 'page']), ['offer' => $key ?: null])))); ?>"
                    class="shrink-0 rounded-full px-4 py-2 text-sm font-medium backdrop-blur-sm transition <?php echo e($active ? 'bg-gold-400 text-royal-950' : 'bg-white/10 text-white/85 hover:bg-white/20'); ?>"><?php echo e($label); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
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

    <div class="page grid gap-8 pt-8 lg:grid-cols-[18rem_1fr]" x-data="{ filters: false }">
        
        <aside>
            <div x-show="filters" x-cloak x-transition.opacity class="fixed inset-0 z-[60] bg-royal-950/50 lg:hidden" @click="filters = false"></div>
            <form method="get" action="<?php echo e(route('properties.index')); ?>"
                class="fixed inset-y-0 left-0 z-[61] w-[88%] max-w-sm -translate-x-full overflow-y-auto bg-white p-6 shadow-lift transition-transform duration-300 lg:sticky lg:top-24 lg:z-auto lg:overflow-visible lg:w-auto lg:max-w-none lg:translate-x-0 lg:rounded-3xl lg:shadow-soft"
                :class="filters && '!translate-x-0'">
                <div class="flex items-center justify-between">
                    <p class="font-display text-xl text-royal-950">Filters</p>
                    <button type="button" class="grid size-9 place-items-center rounded-full hover:bg-royal-50 lg:hidden" @click="filters = false" aria-label="Close filters"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
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
                <?php $__currentLoopData = ['sort', 'view']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(filled($filters[$keep] ?? null)): ?><input type="hidden" name="<?php echo e($keep); ?>" value="<?php echo e($filters[$keep]); ?>"><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <div class="mt-5 space-y-4">
                    <div>
                        <label class="label" for="f-q">Keyword</label>
                        <input id="f-q" name="q" value="<?php echo e($filters['q'] ?? ''); ?>" class="input" placeholder="Area, name or ref">
                    </div>
                    <div>
                        <label class="label" for="f-offer">Looking to</label>
                        <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['id' => 'f-offer','name' => 'offer','label' => 'Looking to','placeholder' => 'Any','icon' => 'key','options' => \App\Models\Property::OFFERS,'value' => $filters['offer'] ?? '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'f-offer','name' => 'offer','label' => 'Looking to','placeholder' => 'Any','icon' => 'key','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Models\Property::OFFERS),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filters['offer'] ?? '')]); ?>
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
                    </div>
                    <div>
                        <label class="label" for="f-category">Property type</label>
                        <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['id' => 'f-category','name' => 'category','label' => 'Property type','placeholder' => 'Any type','icon' => 'home','options' => $categories->pluck('name', 'slug')->all(),'value' => $filters['category'] ?? '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'f-category','name' => 'category','label' => 'Property type','placeholder' => 'Any type','icon' => 'home','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories->pluck('name', 'slug')->all()),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filters['category'] ?? '')]); ?>
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
                    </div>
                    <div>
                        <label class="label" for="f-location">Location</label>
                        <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['id' => 'f-location','name' => 'location','label' => 'Location','placeholder' => 'Anywhere','icon' => 'map-pin','options' => $locations->combine($locations)->all(),'value' => $filters['location'] ?? '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'f-location','name' => 'location','label' => 'Location','placeholder' => 'Anywhere','icon' => 'map-pin','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($locations->combine($locations)->all()),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filters['location'] ?? '')]); ?>
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
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="label" for="f-beds">Beds</label>
                            <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['id' => 'f-beds','name' => 'beds','label' => 'Beds','placeholder' => 'Any','icon' => 'bed','options' => collect([1, 2, 3, 4, 5])->mapWithKeys(fn ($n) => [$n => $n.'+'])->all(),'value' => $filters['beds'] ?? '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'f-beds','name' => 'beds','label' => 'Beds','placeholder' => 'Any','icon' => 'bed','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(collect([1, 2, 3, 4, 5])->mapWithKeys(fn ($n) => [$n => $n.'+'])->all()),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filters['beds'] ?? '')]); ?>
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
                        </div>
                        <div>
                            <label class="label" for="f-baths">Baths</label>
                            <?php if (isset($component)) { $__componentOriginaled2cde6083938c436304f332ba96bb7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled2cde6083938c436304f332ba96bb7c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.select','data' => ['id' => 'f-baths','name' => 'baths','label' => 'Baths','placeholder' => 'Any','icon' => 'bath','options' => collect([1, 2, 3, 4])->mapWithKeys(fn ($n) => [$n => $n.'+'])->all(),'value' => $filters['baths'] ?? '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'f-baths','name' => 'baths','label' => 'Baths','placeholder' => 'Any','icon' => 'bath','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(collect([1, 2, 3, 4])->mapWithKeys(fn ($n) => [$n => $n.'+'])->all()),'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filters['baths'] ?? '')]); ?>
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
                        </div>
                    </div>
                    <div>
                        <span class="label">Price (GH₵)</span>
                        <div class="grid grid-cols-2 gap-3">
                            <input name="min" type="number" min="0" step="any" inputmode="numeric" value="<?php echo e($filters['min'] ?? ''); ?>" class="input" placeholder="Min" aria-label="Minimum price">
                            <input name="max" type="number" min="0" step="any" inputmode="numeric" value="<?php echo e($filters['max'] ?? ''); ?>" class="input" placeholder="Max" aria-label="Maximum price">
                        </div>
                        <p class="mt-1.5 text-xs text-ink/45">US$ listings are converted at GH₵ <?php echo e(\App\Models\Setting::get('usd_rate')); ?>.</p>
                    </div>
                    <label class="flex cursor-pointer items-center gap-3 text-sm">
                        <input type="checkbox" name="furnished" value="1" <?php if(! empty($filters['furnished'])): echo 'checked'; endif; ?> class="size-4 rounded accent-royal-700"> Furnished only
                    </label>
                    <?php if($amenities->isNotEmpty()): ?>
                        <div x-data="{ more: <?php echo e(count($filters['amenities'] ?? []) ? 'true' : 'false'); ?> }">
                            <span class="label">Amenities</span>
                            <div class="grid grid-cols-2 gap-x-3 gap-y-2 text-sm">
                                <?php $__currentLoopData = $amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex cursor-pointer items-center gap-2" <?php if($i >= 6): ?> x-show="more" x-cloak <?php endif; ?>>
                                        <input type="checkbox" name="amenities[]" value="<?php echo e($a->id); ?>" <?php if(in_array($a->id, array_map('intval', $filters['amenities'] ?? []), true)): echo 'checked'; endif; ?> class="size-4 rounded accent-royal-700"> <?php echo e($a->name); ?>

                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php if($amenities->count() > 6): ?>
                                <button type="button" class="mt-2 text-xs font-semibold text-royal-700" @click="more = !more" x-text="more ? 'Show fewer' : 'Show all <?php echo e($amenities->count()); ?>'"></button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="mt-6 grid gap-2">
                    <button class="btn-primary w-full py-3">Show results</button>
                    <?php if($activeFilters): ?><a href="<?php echo e(route('properties.index')); ?>" class="btn-ghost w-full">Clear all</a><?php endif; ?>
                </div>
            </form>
        </aside>

        
        <div>
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" class="btn-ghost lg:hidden" @click="filters = true"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'sliders','class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'sliders','class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?> Filters <?php if($activeFilters): ?><span class="grid size-5 place-items-center rounded-full bg-gold-400 text-[10px] text-royal-950"><?php echo e($activeFilters); ?></span><?php endif; ?></button>
                <form method="get" class="ml-auto flex items-center gap-2">
                    <?php $__currentLoopData = request()->except(['sort', 'page']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(is_array($value)): ?>
                            <?php $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><input type="hidden" name="<?php echo e($key); ?>[]" value="<?php echo e($v); ?>"><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($value); ?>">
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <label for="sort" class="sr-only">Sort</label>
                    <select id="sort" name="sort" class="input w-auto bg-white py-2.5 shadow-soft" data-autosubmit>
                        <?php $__currentLoopData = \App\Models\Property::SORTS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php if(($filters['sort'] ?? 'newest') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </form>
                <div class="flex rounded-full bg-white p-1 shadow-soft">
                    <?php $__currentLoopData = ['grid' => 'Grid view', 'list' => 'List view']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['view' => $mode])); ?>" aria-label="<?php echo e($label); ?>"
                            class="grid size-9 place-items-center rounded-full transition <?php echo e($view === $mode ? 'bg-royal-800 text-white' : 'text-ink/50 hover:text-royal-800'); ?>"><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => $mode,'class' => 'size-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($mode),'class' => 'size-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <?php if($chips): ?>
                <div class="mt-4 flex flex-wrap gap-2">
                    <?php $__currentLoopData = $chips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e($chip['url']); ?>" class="chip gap-1.5 hover:bg-royal-100"><?php echo e($chip['label']); ?> <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x','class' => 'size-3.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'size-3.5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?><span class="sr-only">Remove filter</span></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <?php if($properties->isEmpty()): ?>
                <div class="card mt-6 px-8 py-16 text-center">
                    <p class="font-display text-2xl text-royal-950">Nothing matches yet</p>
                    <p class="mx-auto mt-2 max-w-md text-ink/60">Try fewer filters, or tell us what you need and we will look for you. New homes come in every week.</p>
                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                        <a href="<?php echo e(route('properties.index')); ?>" class="btn-ghost">Clear filters</a>
                        <a href="<?php echo e(route('contact')); ?>" class="btn-primary">Ask an agent</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="mt-6 grid gap-6 <?php echo e($view === 'list' ? '' : 'sm:grid-cols-2 xl:grid-cols-3'); ?>">
                    <?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if (isset($component)) { $__componentOriginal58439408c0a13a2b2db6109a4cd58e23 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal58439408c0a13a2b2db6109a4cd58e23 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.property-card','data' => ['property' => $property,'layout' => $view]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['property' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($property),'layout' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($view)]); ?>
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
                <div class="mt-10"><?php echo e($properties->onEachSide(1)->links()); ?></div>
            <?php endif; ?>
        </div>
    </div>
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
<?php /**PATH C:\Users\princ\OneDrive\Desktop\Personal\luwoap-new\resources\views/site/properties/index.blade.php ENDPATH**/ ?>