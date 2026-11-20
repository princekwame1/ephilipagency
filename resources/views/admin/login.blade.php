<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Sign in | {{ \App\Models\Setting::get('business_name') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-screen bg-royal-950 lg:grid-cols-2 lg:bg-white">
    {{-- Photo: full screen behind the form on phones, left half on large screens --}}
    <div class="fixed inset-0 overflow-hidden bg-royal-950 lg:relative">
        <img src="{{ \App\Models\Setting::heroImageUrl() }}" alt="" class="absolute inset-0 size-full object-cover opacity-60 lg:opacity-50">
        <div class="absolute inset-0 bg-gradient-to-t from-royal-950 via-royal-950/50 to-royal-950/20 lg:via-royal-950/60 lg:to-transparent"></div>
        <div class="absolute bottom-0 hidden p-14 text-white lg:block">
            <x-logo light />
            <p class="mt-6 max-w-md font-display text-3xl leading-snug">Manage listings, viewings and leads in one place.</p>
        </div>
    </div>
    <div class="relative flex items-center justify-center p-5 lg:p-6">
        <form method="post" action="{{ route('admin.login') }}" class="w-full max-w-sm rounded-3xl bg-white p-7 shadow-2xl lg:rounded-none lg:p-0 lg:shadow-none">
            @csrf
            <div class="flex justify-center lg:hidden"><x-logo /></div>
            <h1 class="mt-8 text-center font-display text-3xl text-royal-950 lg:mt-0">Welcome back</h1>
            <p class="mt-2 text-center text-sm text-ink/60">Sign in to the dashboard.</p>
            <div class="mt-8 space-y-4">
                <div>
                    <label class="label" for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="input">
                    @error('email')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label" for="password">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password" class="input">
                </div>
                <label class="flex items-center gap-2 text-sm text-ink/70"><input type="checkbox" name="remember" value="1" class="size-4 accent-royal-700"> Keep me signed in</label>
                <button class="btn-primary w-full py-3">Sign in</button>
            </div>
            <a href="{{ route('home') }}" class="mt-6 block text-center text-sm lg:mt-8 text-ink/50 hover:text-royal-700">Back to website</a>
        </form>
    </div>
</body>
</html>
