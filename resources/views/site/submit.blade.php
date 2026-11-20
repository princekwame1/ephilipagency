<x-layouts.site overlay title="List your property" description="Rent, sell or lease your property with us. Send the details and our team will be in touch within one working day.">
    {{-- Wording: Admin → Site content → List your property --}}
    <x-page-hero :eyebrow="site('submit_eyebrow')" :title="site('submit_title')">
        @if (site('submit_text'))<p class="mt-5 max-w-xl text-white/75">{{ site('submit_text') }}</p>@endif
    </x-page-hero>
    <section class="page grid gap-12 pt-14 lg:grid-cols-[1fr_1.4fr]">
        <div>
            <ol class="space-y-6">
                @foreach (collect(range(1, 3))->map(fn ($n) => [site("submit_step_{$n}_title"), site("submit_step_{$n}_text")])->filter(fn ($step) => $step[0] !== '')->values() as $i => [$title, $text])
                    <li class="flex gap-5">
                        <span class="font-display text-3xl text-gold-500">0{{ $i + 1 }}</span>
                        <div>
                            <p class="font-semibold text-royal-950">{{ $title }}</p>
                            <p class="mt-1 text-sm text-ink/60">{{ $text }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>

        <div class="card p-6 sm:p-10">
            @if (session('sent'))
                <div class="py-12 text-center">
                    <p class="font-display text-3xl text-royal-950">{{ site('submit_success_title') }}</p>
                    <p class="mt-3 text-ink/65">{{ session('sent') }}</p>
                    <a href="{{ route('home') }}" class="btn-primary mt-8">Back to home</a>
                </div>
            @else
                <form method="post" action="{{ route('submit.store') }}" enctype="multipart/form-data" class="space-y-6" x-data="{ files: [], video: '' }">
                    @csrf
                    <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

                    <fieldset class="space-y-4">
                        <legend class="font-display text-xl text-royal-950">Your details</legend>
                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <label class="label" for="s-name">Name</label>
                                <input id="s-name" name="name" value="{{ old('name') }}" required class="input" autocomplete="name">
                                @error('name')<p class="error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="label" for="s-phone">Phone</label>
                                <input id="s-phone" name="phone" type="tel" value="{{ old('phone') }}" required class="input" autocomplete="tel">
                                @error('phone')<p class="error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="label" for="s-email">Email <span class="font-normal text-ink/40">(optional)</span></label>
                                <input id="s-email" name="email" type="email" value="{{ old('email') }}" class="input" autocomplete="email">
                                @error('email')<p class="error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="space-y-4">
                        <legend class="font-display text-xl text-royal-950">The property</legend>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="label" for="s-offer">I want to</label>
                                <select id="s-offer" name="offer_type" class="input">
                                    @foreach (['rent' => 'Rent it out', 'sale' => 'Sell it', 'lease' => 'Lease it', 'short_stay' => 'Offer short stays'] as $key => $label)
                                        <option value="{{ $key }}" @selected(old('offer_type') === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="label" for="s-category">Type</label>
                                <select id="s-category" name="category_id" class="input">
                                    <option value="">Not sure</option>
                                    @foreach ($categories as $c)
                                        <option value="{{ $c->id }}" @selected(old('category_id') == $c->id)>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="label" for="s-location">Location</label>
                                <input id="s-location" name="location" value="{{ old('location') }}" required class="input" placeholder="e.g. Adenta, near the police station">
                                @error('location')<p class="error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="label" for="s-price">Asking price</label>
                                <div class="flex gap-2">
                                    <select name="currency" class="input w-28" aria-label="Currency">
                                        @foreach (\App\Models\Property::CURRENCIES as $code => $symbol)
                                            <option value="{{ $code }}" @selected(old('currency', 'GHS') === $code)>{{ $symbol }}</option>
                                        @endforeach
                                    </select>
                                    <input id="s-price" name="price" type="number" min="0" step="any" value="{{ old('price') }}" class="input" placeholder="Optional">
                                </div>
                                @error('price')<p class="error">{{ $message }}</p>@enderror
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="label" for="s-beds">Bedrooms</label>
                                    <input id="s-beds" name="bedrooms" type="number" min="0" max="30" value="{{ old('bedrooms') }}" class="input">
                                </div>
                                <div>
                                    <label class="label" for="s-baths">Bathrooms</label>
                                    <input id="s-baths" name="bathrooms" type="number" min="0" max="30" value="{{ old('bathrooms') }}" class="input">
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="label" for="s-desc">Anything else?</label>
                                <textarea id="s-desc" name="description" rows="4" class="input" placeholder="Condition, furnishing, water and power, parking, when it is available...">{{ old('description') }}</textarea>
                            </div>
                            <div class="sm:col-span-2">
                                <span class="label">Photos <span class="font-normal text-ink/40">(up to 8, optional)</span></span>
                                <label class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-2xl bg-royal-50/70 px-6 py-8 text-center text-sm text-ink/60 transition hover:bg-royal-50">
                                    <x-icon name="upload" class="size-6 text-royal-600" />
                                    <span x-text="files.length ? files.length + ' photo' + (files.length > 1 ? 's' : '') + ' selected' : 'Choose photos (JPG, PNG or WebP)'"></span>
                                    <input type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple class="sr-only" @change="files = [...$event.target.files]">
                                </label>
                                @error('photos')<p class="error">{{ $message }}</p>@enderror
                                @error('photos.*')<p class="error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <span class="label">Video <span class="font-normal text-ink/40">(optional, up to {{ intdiv(\App\Models\Submission::VIDEO_MAX_KB, 1024) }} MB)</span></span>
                                <label class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-2xl bg-royal-50/70 px-6 py-6 text-center text-sm text-ink/60 transition hover:bg-royal-50">
                                    <x-icon name="upload" class="size-6 text-royal-600" />
                                    <span x-text="video || 'Choose a video (MP4, MOV or WebM)'"></span>
                                    <input type="file" name="video" accept="video/mp4,video/quicktime,video/webm" class="sr-only" @change="video = $event.target.files[0]?.name ?? ''">
                                </label>
                                @error('video')<p class="error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="label" for="s-video-url">Or a video link <span class="font-normal text-ink/40">(optional)</span></label>
                                <input id="s-video-url" name="video_url" type="url" value="{{ old('video_url') }}" class="input" placeholder="YouTube, TikTok, Google Drive...">
                                @error('video_url')<p class="error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </fieldset>

                    <button class="btn-primary w-full py-3.5">{{ site('submit_button') }}</button>
                </form>
            @endif
        </div>
    </section>
</x-layouts.site>
