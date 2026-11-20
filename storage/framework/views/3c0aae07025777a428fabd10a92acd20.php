
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'eyebrow' => null]));

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

foreach (array_filter((['title', 'eyebrow' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<section <?php echo e($attributes->merge(['class' => 'relative isolate overflow-hidden bg-royal-950 pb-12 pt-32 text-white sm:pb-16 sm:pt-40'])); ?>>
    <div class="absolute inset-0 -z-10" aria-hidden="true">
        <img src="<?php echo e(asset('images/page-hero.webp')); ?>"
            srcset="<?php echo e(asset('images/page-hero-900.webp')); ?> 900w, <?php echo e(asset('images/page-hero.webp')); ?> 1920w"
            sizes="100vw" width="1920" height="1080" alt="" fetchpriority="high" decoding="async"
            class="absolute inset-0 size-full object-cover object-[70%_45%] animate-hero-zoom">
        
        <div class="absolute inset-0 bg-gradient-to-r from-royal-950/90 via-royal-950/65 to-royal-950/20 max-sm:via-royal-950/75 max-sm:to-royal-950/55"></div>
        <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-b from-royal-950/70 to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-royal-950/80 to-transparent"></div>
        <div class="absolute -bottom-32 -left-24 size-[24rem] rounded-full bg-gold-500/15 blur-3xl"></div>
        <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-gold-400/60 to-transparent"></div>
    </div>

    <div class="page">
        <div class="animate-fade-up">
            <?php if($eyebrow): ?><p class="eyebrow text-gold-300"><?php echo e($eyebrow); ?></p><?php endif; ?>
            <h1 class="mt-3 max-w-3xl font-display text-4xl leading-tight sm:text-5xl"><?php echo e($title); ?></h1>
            <?php echo e($slot); ?>

        </div>
    </div>
</section>
<?php /**PATH C:\Users\princ\OneDrive\Desktop\Personal\luwoap-new\resources\views/components/page-hero.blade.php ENDPATH**/ ?>