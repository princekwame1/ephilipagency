<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Sign in | <?php echo e(\App\Models\Setting::get('business_name')); ?></title>
    <link rel="icon" href="<?php echo e(asset('favicon.svg')); ?>" type="image/svg+xml">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="grid min-h-screen bg-royal-950 lg:grid-cols-2 lg:bg-white">
    
    <div class="fixed inset-0 overflow-hidden bg-royal-950 lg:relative">
        <img src="<?php echo e(\App\Models\Setting::heroImageUrl()); ?>" alt="" class="absolute inset-0 size-full object-cover opacity-60 lg:opacity-50">
        <div class="absolute inset-0 bg-gradient-to-t from-royal-950 via-royal-950/50 to-royal-950/20 lg:via-royal-950/60 lg:to-transparent"></div>
        <div class="absolute bottom-0 hidden p-14 text-white lg:block">
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
            <p class="mt-6 max-w-md font-display text-3xl leading-snug">Manage listings, viewings and leads in one place.</p>
        </div>
    </div>
    <div class="relative flex items-center justify-center p-5 lg:p-6">
        <form method="post" action="<?php echo e(route('admin.login')); ?>" class="w-full max-w-sm rounded-3xl bg-white p-7 shadow-2xl lg:rounded-none lg:p-0 lg:shadow-none">
            <?php echo csrf_field(); ?>
            <div class="flex justify-center lg:hidden"><?php if (isset($component)) { $__componentOriginal987d96ec78ed1cf75b349e2e5981978f = $component; } ?>
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
<?php endif; ?></div>
            <h1 class="mt-8 text-center font-display text-3xl text-royal-950 lg:mt-0">Welcome back</h1>
            <p class="mt-2 text-center text-sm text-ink/60">Sign in to the dashboard.</p>
            <div class="mt-8 space-y-4">
                <div>
                    <label class="label" for="email">Email</label>
                    <input id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" required autofocus autocomplete="username" class="input">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="error"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="label" for="password">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password" class="input">
                </div>
                <label class="flex items-center gap-2 text-sm text-ink/70"><input type="checkbox" name="remember" value="1" class="size-4 accent-royal-700"> Keep me signed in</label>
                <button class="btn-primary w-full py-3">Sign in</button>
            </div>
            <a href="<?php echo e(route('home')); ?>" class="mt-6 block text-center text-sm lg:mt-8 text-ink/50 hover:text-royal-700">Back to website</a>
        </form>
    </div>
</body>
</html>
<?php /**PATH C:\Users\princ\OneDrive\Desktop\Personal\luwoap-new\resources\views/admin/login.blade.php ENDPATH**/ ?>