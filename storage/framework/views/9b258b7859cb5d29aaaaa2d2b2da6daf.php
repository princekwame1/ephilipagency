<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['property', 'layout' => 'grid']));

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

foreach (array_filter((['property', 'layout' => 'grid']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php ($url = route('properties.show', $property)); ?>
<article <?php echo e($attributes->merge(['class' => 'group relative overflow-hidden rounded-3xl bg-white shadow-soft transition duration-300 hover:-translate-y-1 hover:shadow-lift '.($layout === 'list' ? 'sm:flex' : '')])); ?>>
    <a href="<?php echo e($url); ?>" class="relative block overflow-hidden <?php echo e($layout === 'list' ? 'aspect-[4/3] sm:aspect-auto sm:w-80 sm:shrink-0' : 'aspect-[4/3]'); ?>">
        <img src="<?php echo e($property->coverUrl()); ?>" alt="<?php echo e($property->title); ?>" loading="lazy"
            class="size-full object-cover transition duration-700 group-hover:scale-105">
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-royal-950/50 to-transparent"></div>
        <div class="absolute left-4 top-4 flex flex-wrap gap-1.5">
            <span class="rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-royal-800 backdrop-blur"><?php echo e($property->offerLabel()); ?></span>
            <?php if($property->is_featured): ?>
                <span class="rounded-full bg-gold-400 px-3 py-1 text-xs font-semibold text-royal-950">Featured</span>
            <?php endif; ?>
            <?php if (! ($property->isAvailable())): ?>
                <span class="rounded-full bg-royal-950/80 px-3 py-1 text-xs font-semibold text-white"><?php echo e($property->availabilityLabel()); ?></span>
            <?php endif; ?>
        </div>
        <p class="absolute bottom-3 left-4 text-lg font-semibold text-white drop-shadow">
            <?php echo e($property->formattedPrice()); ?>

            <?php if($period = $property->periodLabel()): ?><span class="text-sm font-normal text-white/80"><?php echo e($period); ?></span><?php endif; ?>
        </p>
    </a>

    <button type="button" x-data @click="$store.saved.toggle(<?php echo e($property->id); ?>)"
        class="absolute right-4 top-4 z-10 grid size-10 place-items-center rounded-full bg-white/95 text-royal-800 shadow-soft backdrop-blur transition hover:scale-110"
        :class="$store.saved.has(<?php echo e($property->id); ?>) && 'text-gold-500'"
        :aria-pressed="$store.saved.has(<?php echo e($property->id); ?>)" aria-label="Save <?php echo e($property->title); ?>">
        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'heart','class' => 'size-5',':class' => '$store.saved.has('.e($property->id).') && \'fill-current\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'heart','class' => 'size-5',':class' => '$store.saved.has('.e($property->id).') && \'fill-current\'']); ?>
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

    <div class="flex flex-1 flex-col p-5 <?php echo e($layout === 'list' ? 'sm:p-6' : ''); ?>">
        <p class="text-xs font-medium uppercase tracking-wider text-gold-600"><?php echo e($property->category?->name ?? 'Property'); ?> · <?php echo e($property->location); ?></p>
        <h3 class="mt-1.5 font-display text-lg font-semibold leading-snug text-royal-950">
            <a href="<?php echo e($url); ?>" class="after:absolute after:inset-0 after:content-[''] focus:outline-none"><?php echo e($property->title); ?></a>
        </h3>
        <?php if($layout === 'list' && $property->summary): ?>
            <p class="mt-2 line-clamp-2 text-sm text-ink/60"><?php echo e($property->summary); ?></p>
        <?php endif; ?>
        <p class="mt-auto pt-4 text-sm text-ink/60"><?php echo e($property->factsLine()); ?><?php if($property->furnished): ?> · Furnished <?php endif; ?></p>
    </div>
</article>
<?php /**PATH C:\Users\princ\OneDrive\Desktop\Personal\luwoap-new\resources\views/components/property-card.blade.php ENDPATH**/ ?>