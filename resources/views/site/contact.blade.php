@php($s = \App\Models\Setting::values())
<x-layouts.site overlay title="Contact us">
    {{-- Wording: Admin → Site content → Contact page --}}
    <x-page-hero :eyebrow="site('contact_eyebrow')" :title="site('contact_title')">
        @if (site('contact_text'))<p class="mt-5 max-w-xl text-white/75">{{ site('contact_text') }}</p>@endif
    </x-page-hero>
    <section class="page grid gap-12 pt-14 lg:grid-cols-[1fr_1.2fr]">
        <div>
            <dl class="grid gap-6 sm:grid-cols-2">
                @if ($s['phone'])
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-ink/45">Call</dt><dd class="mt-1"><a href="tel:{{ \App\Models\Setting::tel($s['phone']) }}" class="text-lg font-semibold text-royal-900 hover:text-gold-600">{{ $s['phone'] }}</a></dd></div>
                @endif
                @if ($wa = \App\Models\Setting::whatsappUrl())
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-ink/45">WhatsApp</dt><dd class="mt-1"><a href="{{ $wa }}" target="_blank" rel="noopener" class="text-lg font-semibold text-royal-900 hover:text-gold-600">{{ $s['whatsapp'] }}</a></dd></div>
                @endif
                @if ($s['email'])
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-ink/45">Email</dt><dd class="mt-1"><a href="mailto:{{ $s['email'] }}" class="text-lg font-semibold text-royal-900 hover:text-gold-600">{{ $s['email'] }}</a></dd></div>
                @endif
                @if ($s['address'])
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-ink/45">Office</dt><dd class="mt-1 text-lg font-semibold text-royal-900">{{ $s['address'] }}</dd>@if ($s['hours'])<dd class="text-sm text-ink/55">{{ $s['hours'] }}</dd>@endif</div>
                @endif
            </dl>
        </div>

        <div class="card p-6 sm:p-10">
            @if (session('sent'))
                <div class="py-12 text-center">
                    <p class="font-display text-3xl text-royal-950">{{ site('contact_success_title') }}</p>
                    <p class="mt-3 text-ink/65">{{ session('sent') }}</p>
                    <a href="{{ route('properties.index') }}" class="btn-primary mt-8">Browse properties meanwhile</a>
                </div>
            @else
                <form method="post" action="{{ route('contact.send') }}" class="space-y-4">
                    @csrf
                    <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
                    <div>
                        <label class="label" for="c-name">Your name</label>
                        <input id="c-name" name="name" value="{{ old('name') }}" required class="input" autocomplete="name">
                        @error('name')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="label" for="c-phone">Phone</label>
                            <input id="c-phone" name="phone" type="tel" value="{{ old('phone') }}" required class="input" autocomplete="tel">
                            @error('phone')<p class="error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label" for="c-email">Email <span class="font-normal text-ink/40">(optional)</span></label>
                            <input id="c-email" name="email" type="email" value="{{ old('email') }}" class="input" autocomplete="email">
                            @error('email')<p class="error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label class="label" for="c-message">{{ site('contact_message_label') }}</label>
                        <textarea id="c-message" name="message" rows="6" required class="input" placeholder="{{ site('contact_message_hint') }}">{{ old('message') }}</textarea>
                        @error('message')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <button class="btn-primary w-full py-3.5">{{ site('contact_button') }}</button>
                </form>
            @endif
        </div>
    </section>
</x-layouts.site>
