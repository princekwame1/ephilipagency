
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'options' => [], 'value' => null, 'placeholder' => 'Any', 'label' => null, 'icon' => null, 'id' => null]));

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

foreach (array_filter((['name', 'options' => [], 'value' => null, 'placeholder' => 'Any', 'label' => null, 'icon' => null, 'id' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $id ??= $name.'-'.Str::random(4);
    $items = collect(['' => $placeholder] + $options)->map(fn ($l, $v) => ['value' => (string) $v, 'label' => (string) $l])->values();
?>
<div <?php echo e($attributes->merge(['class' => 'relative'])); ?>

    x-data="{
        open: false,
        value: <?php echo \Illuminate\Support\Js::from((string) $value)->toHtml() ?>,
        items: <?php echo \Illuminate\Support\Js::from($items)->toHtml() ?>,
        active: 0,
        get current() { return this.items.find(i => i.value === this.value) ?? this.items[0] },
        show() { this.open = true; this.active = Math.max(0, this.items.findIndex(i => i.value === this.value)); this.$nextTick(() => this.scrollToActive()) },
        choose(i) { this.value = this.items[i].value; this.open = false; this.$refs.button.focus() },
        move(step) { if (! this.open) return this.show(); this.active = (this.active + step + this.items.length) % this.items.length; this.scrollToActive() },
        jump(key) { const i = this.items.findIndex((it, n) => n > 0 && it.label.toLowerCase().startsWith(key.toLowerCase())); if (i > -1) { this.open || this.show(); this.active = i; this.scrollToActive() } },
        scrollToActive() { this.$refs.list?.children[this.active]?.scrollIntoView({ block: 'nearest' }) },
    }"
    @click.outside="open = false" @keydown.escape.prevent.stop="open = false; $refs.button.focus()">
    <input type="hidden" name="<?php echo e($name); ?>" :value="value" :disabled="value === ''" value="<?php echo e($value); ?>" <?php if(blank($value)): echo 'disabled'; endif; ?>>
    <?php if($label): ?><span class="sr-only" id="<?php echo e($id); ?>-label"><?php echo e($label); ?></span><?php endif; ?>

    <button type="button" x-ref="button" id="<?php echo e($id); ?>"
        @click="open ? open = false : show()"
        @keydown.arrow-down.prevent="move(1)" @keydown.arrow-up.prevent="move(-1)"
        @keydown.enter.prevent="open ? choose(active) : show()" @keydown.space.prevent="open ? choose(active) : show()"
        @keydown.tab="open = false" @keydown="$event.key.length === 1 && $event.key !== ' ' && jump($event.key)"
        aria-haspopup="listbox" :aria-expanded="open" <?php if($label): ?> aria-labelledby="<?php echo e($id); ?>-label <?php echo e($id); ?>" <?php endif; ?>
        class="group flex h-full w-full items-center gap-2.5 rounded-xl bg-royal-50/60 px-4 py-3 text-left text-sm text-ink ring-1 ring-transparent transition hover:bg-royal-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-400"
        :class="open && 'bg-white ring-2 ring-gold-400'">
        <?php if($icon): ?><?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => $icon,'class' => 'size-4 shrink-0 text-royal-600']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'class' => 'size-4 shrink-0 text-royal-600']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?><?php endif; ?>
        <span class="min-w-0 flex-1 truncate" :class="value === '' ? 'text-ink/60' : 'font-medium text-royal-950'" x-text="current.label"><?php echo e($items->firstWhere('value', (string) $value)['label'] ?? $placeholder); ?></span>
        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'chevron-down','class' => 'size-4 shrink-0 text-royal-600 transition duration-200',':class' => 'open && \'rotate-180\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-down','class' => 'size-4 shrink-0 text-royal-600 transition duration-200',':class' => 'open && \'rotate-180\'']); ?>
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

    <ul x-ref="list" x-show="open" x-cloak role="listbox" <?php if($label): ?> aria-labelledby="<?php echo e($id); ?>-label" <?php endif; ?>
        x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1 scale-[0.98]" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute left-0 z-40 mt-2 max-h-72 w-full min-w-48 origin-top overflow-y-auto overscroll-contain rounded-2xl bg-white p-1.5 shadow-lift ring-1 ring-royal-100">
        <template x-for="(item, i) in items" :key="item.value">
            <li role="option" :aria-selected="item.value === value" @click="choose(i)" @mouseenter="active = i"
                class="flex cursor-pointer items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors"
                :class="{
                    'bg-royal-50 text-royal-950': active === i && item.value !== value,
                    'bg-royal-800 text-white': item.value === value,
                    'text-ink/75': active !== i && item.value !== value,
                }">
                <span x-text="item.label" :class="item.value === value && 'font-semibold'"></span>
                <svg x-show="item.value === value" class="size-4 shrink-0 text-gold-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-8 8a1 1 0 0 1-1.4 0l-4-4a1 1 0 1 1 1.4-1.4L8 12.6l7.3-7.3a1 1 0 0 1 1.4 0z"/></svg>
            </li>
        </template>
    </ul>
</div>
<?php /**PATH C:\Users\princ\OneDrive\Desktop\Personal\luwoap-new\resources\views/components/select.blade.php ENDPATH**/ ?>