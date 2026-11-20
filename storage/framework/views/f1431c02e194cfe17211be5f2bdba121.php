<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['light' => false]));

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

foreach (array_filter((['light' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $name = \App\Models\Setting::get('business_name');
    [$first, $rest] = array_pad(explode(' ', trim($name), 2), 2, '');
    $uid = 'lg'.Str::random(6); // gradient ids must be unique: the header renders the logo more than once
?>
<?php if($logo = \App\Models\Setting::logoUrl()): ?>
    <img src="<?php echo e($logo); ?>" alt="<?php echo e($name); ?>" <?php echo e($attributes->merge(['class' => 'h-10 w-auto'])); ?>>
<?php else: ?>
    <span <?php echo e($attributes->merge(['class' => 'inline-flex items-center gap-2.5'])); ?>>
        
        <svg class="h-10 w-11 shrink-0" viewBox="6 6 50 48" aria-hidden="true">
            <defs>
                <linearGradient id="<?php echo e($uid); ?>g" x1="0" y1="0" x2="0" y2="1">
                    <?php $__currentLoopData = $light ? ["#f7eccf", "#e5c06a", "#c9922f", "#efd89e", "#ab7325"] : ["#dbab47", "#c9922f", "#8a5621", "#c9922f", "#724621"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <stop offset="<?php echo e([0, .38, .62, .82, 1][$i]); ?>" stop-color="<?php echo e($c); ?>"/>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </linearGradient>
            </defs>
            <g fill="url(#<?php echo e($uid); ?>g)">
                <path d="M9 37 30 17l17 16h-4L30 21.5 13.5 37z"/>
                <rect x="25.5" y="27.5" width="3.8" height="3.8" rx=".45"/><rect x="30.7" y="27.5" width="3.8" height="3.8" rx=".45"/>
                <rect x="25.5" y="32.7" width="3.8" height="3.8" rx=".45"/><rect x="30.7" y="32.7" width="3.8" height="3.8" rx=".45"/>
                <path fill-rule="evenodd" d="M48 8a8 8 0 1 1 0 16 8 8 0 1 1 0-16zm0 2.6a5.4 5.4 0 1 0 0 10.8 5.4 5.4 0 1 0 0-10.8z"/>
                <path d="M46.6 24h2.8v26L48 52.5 46.6 50z"/>
                <path d="M49.4 40H54v2.2h-2v1.4h2v2.8h-4.6z"/>
                <path d="M8 44c12-4.5 26-4.5 36-1.5-10-.9-24-.3-36 3.5z" opacity=".9"/>
            </g>
        </svg>
        <?php if($rest !== ''): ?>
            
            <span class="flex flex-col leading-none">
                <span class="font-display text-[1.4rem] font-semibold tracking-tight <?php echo e($light ? 'text-white' : 'text-royal-950'); ?>"><?php echo e($first); ?></span>
                <span class="mt-1 whitespace-nowrap text-[0.72rem] font-semibold uppercase tracking-[0.18em] <?php echo e($light ? 'text-gold-300' : 'text-gold-700'); ?>"><?php echo e($rest); ?></span>
            </span>
        <?php else: ?>
            <span class="font-display text-[1.35rem] font-semibold leading-none tracking-tight <?php echo e($light ? 'text-white' : 'text-royal-950'); ?>"><?php echo e($name); ?></span>
        <?php endif; ?>
    </span>
<?php endif; ?>
<?php /**PATH C:\Users\princ\OneDrive\Desktop\Personal\luwoap-new\resources\views/components/logo.blade.php ENDPATH**/ ?>