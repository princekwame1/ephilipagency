@php
    $field = fn ($key, $label, $type = 'text', $hint = null) => compact('key', 'label', 'type', 'hint');
    $sections = [
        'Brand' => [
            $field('business_name', 'Business name'),
            $field('tagline', 'Tagline'),
        ],
        'Contact details' => [
            $field('phone', 'Phone'),
            $field('whatsapp', 'WhatsApp number', 'text', 'Powers the green chat button. Leave blank to hide it.'),
            $field('email', 'Public email', 'email'),
            $field('address', 'Office address'),
            $field('hours', 'Opening hours'),
            $field('notify_email', 'Send new leads to', 'email', 'Every enquiry and submission is emailed here. Needs mail settings in .env.'),
        ],
        'Social links' => [
            $field('facebook', 'Facebook URL', 'url'),
            $field('instagram', 'Instagram URL', 'url'),
            $field('tiktok', 'TikTok URL', 'url'),
            $field('linkedin', 'LinkedIn URL', 'url'),
        ],
        'Prices' => [
            $field('usd_rate', 'GH₵ per US$', 'number', 'Used to compare US$ and GH₵ listings in price filters and the payment calculator.'),
        ],
    ];
@endphp
<x-layouts.admin title="Settings">
    <form method="post" action="{{ route('admin.settings') }}" enctype="multipart/form-data" class="grid max-w-5xl gap-6">
        @csrf @method('put')

        <a href="{{ route('admin.content') }}" class="card flex items-center justify-between gap-4 p-5 ring-1 ring-gold-300/60 transition hover:bg-gold-50">
            <span>
                <span class="block font-semibold text-royal-950">Looking for page wording or photos?</span>
                <span class="text-sm text-ink/55">Headlines, section text, buttons, menu labels and background photos are in Site content.</span>
            </span>
            <span class="btn-ghost shrink-0">Open Site content <x-icon name="arrow-right" class="size-4" /></span>
        </a>

        <section class="card grid gap-6 p-6 md:grid-cols-[14rem_1fr]">
            <div>
                <h2 class="font-semibold text-royal-950">Logo</h2>
                <p class="mt-1 text-sm text-ink/50">PNG with a transparent background works best. Leave empty to use the built-in gold emblem.</p>
            </div>
            <div class="grid gap-6 sm:grid-cols-2">
                @foreach (['logo' => ['Logo', \App\Models\Setting::logoUrl()]] as $input => [$label, $url])
                    <div>
                        <span class="label">{{ $label }}</span>
                        <div class="mb-3 grid h-28 place-items-center overflow-hidden rounded-2xl {{ $input === 'logo' ? 'bg-royal-50' : 'bg-royal-900' }}">
                            @if ($url)
                                <img src="{{ $url }}" alt="" class="{{ $input === 'logo' ? 'max-h-16 w-auto' : 'size-full object-cover' }}">
                            @elseif ($input === 'logo')
                                <x-logo />
                            @else
                                <img src="{{ \App\Models\Setting::heroImageUrl() }}" alt="" class="size-full object-cover opacity-80">
                            @endif
                        </div>
                        <input name="{{ $input }}" type="file" accept="image/png,image/jpeg,image/webp" class="text-sm file:mr-3 file:rounded-full file:border-0 file:bg-royal-50 file:px-4 file:py-2 file:font-semibold file:text-royal-800" aria-label="{{ $label }}">
                        @if ($url)<label class="mt-2 flex items-center gap-2 text-xs text-ink/60"><input type="checkbox" name="remove_{{ $input }}" value="1" class="accent-royal-700"> Remove and use the default</label>@endif
                        @error($input)<p class="error">{{ $message }}</p>@enderror
                    </div>
                @endforeach
            </div>
        </section>

        @foreach ($sections as $title => $fields)
            <section class="card grid gap-6 p-6 md:grid-cols-[14rem_1fr]">
                <h2 class="font-semibold text-royal-950">{{ $title }}</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach ($fields as $f)
                        <div class="{{ $f['type'] === 'textarea' ? 'sm:col-span-2' : '' }}">
                            <label class="label" for="{{ $f['key'] }}">{{ $f['label'] }}</label>
                            @if ($f['type'] === 'textarea')
                                <textarea id="{{ $f['key'] }}" name="{{ $f['key'] }}" rows="{{ $f['key'] === 'about' ? 7 : 3 }}" class="input">{{ old($f['key'], $s[$f['key']]) }}</textarea>
                            @else
                                <input id="{{ $f['key'] }}" name="{{ $f['key'] }}" type="{{ $f['type'] }}" @if ($f['type'] === 'number') step="any" @endif value="{{ old($f['key'], $s[$f['key']]) }}" class="input">
                            @endif
                            @if ($f['hint'])<p class="mt-1.5 text-xs text-ink/45">{{ $f['hint'] }}</p>@endif
                            @error($f['key'])<p class="error">{{ $message }}</p>@enderror
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <div class="sticky bottom-4 flex justify-end">
            <button class="btn-primary px-8 py-3 shadow-lift">Save settings</button>
        </div>
    </form>
</x-layouts.admin>
