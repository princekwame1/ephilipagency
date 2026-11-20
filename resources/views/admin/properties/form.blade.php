@php
    $editing = $property->exists;
    $selectedAmenities = old('amenities', $property->exists ? $property->amenities->pluck('id')->all() : []);
@endphp
<x-layouts.admin :title="$editing ? 'Edit listing' : 'New listing'">
    <x-slot:actions>
        @if ($editing)
            <a href="{{ route('properties.show', $property) }}" target="_blank" class="btn-ghost bg-white">{{ $property->status === 'published' ? 'View' : 'Preview' }}</a>
        @endif
    </x-slot:actions>

    <form method="post" action="{{ $editing ? route('admin.properties.update', $property) : route('admin.properties.store') }}" enctype="multipart/form-data"
        class="grid gap-6 xl:grid-cols-[1fr_22rem]" x-data="{ files: [], video: '' }">
        @csrf
        @if ($editing) @method('put') @endif

        <div class="space-y-6">
            <section class="card space-y-4 p-6">
                <h2 class="font-semibold text-royal-950">Basics</h2>
                <div>
                    <label class="label" for="title">Title</label>
                    <input id="title" name="title" value="{{ old('title', $property->title) }}" required class="input" placeholder="e.g. Furnished 2-bedroom apartment with pool">
                    @error('title')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="label" for="offer_type">Offer</label>
                        <select id="offer_type" name="offer_type" class="input">
                            @foreach (\App\Models\Property::OFFERS as $key => $label)
                                <option value="{{ $key }}" @selected(old('offer_type', $property->offer_type) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label" for="category_id">Type</label>
                        <select id="category_id" name="category_id" class="input">
                            <option value="">Uncategorised</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}" @selected(old('category_id', $property->category_id) == $c->id)>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label" for="agent_id">Agent</label>
                        <select id="agent_id" name="agent_id" class="input">
                            <option value="">Main office</option>
                            @foreach ($agents as $a)
                                <option value="{{ $a->id }}" @selected(old('agent_id', $property->agent_id) == $a->id)>{{ $a->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="label" for="summary">Short summary <span class="font-normal text-ink/40">(shown on cards and search results)</span></label>
                    <input id="summary" name="summary" value="{{ old('summary', $property->summary) }}" maxlength="300" class="input">
                    @error('summary')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label" for="description">Full description</label>
                    <textarea id="description" name="description" rows="12" class="input font-mono text-[13px] leading-6">{{ old('description', $property->description) }}</textarea>
                    <p class="mt-1.5 text-xs text-ink/45">Formatting: **bold**, ## heading, "- " for bullet points, blank line between paragraphs.</p>
                    @error('description')<p class="error">{{ $message }}</p>@enderror
                </div>
            </section>

            <section class="card space-y-4 p-6">
                <h2 class="font-semibold text-royal-950">Price and size</h2>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="label" for="price">Price</label>
                        <input id="price" name="price" type="number" min="0" step="any" value="{{ old('price', $property->price ? (float) $property->price : '') }}" required class="input">
                        <p class="mt-1.5 text-xs text-ink/45">0 shows "Price on request".</p>
                        @error('price')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label" for="currency">Currency</label>
                        <select id="currency" name="currency" class="input">
                            @foreach (\App\Models\Property::CURRENCIES as $code => $symbol)
                                <option value="{{ $code }}" @selected(old('currency', $property->currency) === $code)>{{ $symbol }} ({{ $code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label" for="price_period">Per</label>
                        <select id="price_period" name="price_period" class="input">
                            <option value="">One-off price</option>
                            @foreach (\App\Models\Property::PERIODS as $key => $label)
                                <option value="{{ $key }}" @selected(old('price_period', $property->price_period) === $key)>{{ ucfirst($label) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    @foreach (['bedrooms' => 'Bedrooms', 'bathrooms' => 'Bathrooms', 'area' => 'Floor area (m²)', 'parking' => 'Parking spaces'] as $field => $label)
                        <div>
                            <label class="label" for="{{ $field }}">{{ $label }}</label>
                            <input id="{{ $field }}" name="{{ $field }}" type="number" min="0" value="{{ old($field, $property->$field) }}" class="input" @if (in_array($field, ['bedrooms', 'bathrooms'])) required @endif>
                            @error($field)<p class="error">{{ $message }}</p>@enderror
                        </div>
                    @endforeach
                </div>
                <label class="flex items-center gap-3 text-sm"><input type="checkbox" name="furnished" value="1" @checked(old('furnished', $property->furnished)) class="size-4 accent-royal-700"> Furnished</label>
            </section>

            <section class="card space-y-4 p-6">
                <h2 class="font-semibold text-royal-950">Location</h2>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="label" for="location">Area / neighbourhood</label>
                        <input id="location" name="location" list="locations" value="{{ old('location', $property->location) }}" required class="input" placeholder="e.g. East Legon">
                        <datalist id="locations">@foreach ($locations as $loc)<option value="{{ $loc }}">@endforeach</datalist>
                        @error('location')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label" for="city">City</label>
                        <input id="city" name="city" value="{{ old('city', $property->city) }}" required class="input">
                    </div>
                    <div>
                        <label class="label" for="address">Street / landmark <span class="font-normal text-ink/40">(public)</span></label>
                        <input id="address" name="address" value="{{ old('address', $property->address) }}" class="input">
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label" for="latitude">Latitude</label>
                        <input id="latitude" name="latitude" type="number" step="any" value="{{ old('latitude', $property->latitude) }}" class="input" placeholder="5.7146">
                        @error('latitude')<p class="error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label" for="longitude">Longitude</label>
                        <input id="longitude" name="longitude" type="number" step="any" value="{{ old('longitude', $property->longitude) }}" class="input" placeholder="-0.1564">
                        @error('longitude')<p class="error">{{ $message }}</p>@enderror
                    </div>
                </div>
                <p class="text-xs text-ink/45">Tip: in Google Maps, right-click the spot and click the numbers to copy them. Leave blank to hide the map.</p>
            </section>

            <section class="card p-6">
                <h2 class="font-semibold text-royal-950">Amenities</h2>
                <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2.5 text-sm sm:grid-cols-3 lg:grid-cols-4">
                    @foreach ($amenities as $a)
                        <label class="flex cursor-pointer items-center gap-2.5"><input type="checkbox" name="amenities[]" value="{{ $a->id }}" @checked(in_array($a->id, array_map('intval', $selectedAmenities), true)) class="size-4 accent-royal-700"> {{ $a->name }}</label>
                    @endforeach
                </div>
                <a href="{{ route('admin.amenities.index') }}" class="mt-4 inline-block text-xs font-medium text-royal-700">Manage amenities</a>
            </section>

            <section class="card space-y-4 p-6">
                <div class="flex items-baseline justify-between">
                    <h2 class="font-semibold text-royal-950">Photos and video</h2>
                    <p class="text-xs text-ink/45">The first photo is the cover.</p>
                </div>
                @if ($editing && $property->images->isNotEmpty())
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                        @foreach ($property->images as $i => $image)
                            <div class="group relative aspect-[4/3] overflow-hidden rounded-2xl bg-royal-100">
                                <img src="{{ $image->url() }}" alt="" class="size-full object-cover">
                                @if ($i === 0)<span class="absolute left-2 top-2 rounded-full bg-gold-400 px-2.5 py-0.5 text-[11px] font-bold text-royal-950">Cover</span>@endif
                                <div class="absolute inset-x-2 bottom-2 flex justify-end gap-1 opacity-0 transition group-hover:opacity-100 group-focus-within:opacity-100">
                                    @if ($i > 0)
                                        <button form="cover-{{ $image->id }}" class="rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-royal-900 shadow-soft">Make cover</button>
                                    @endif
                                    <button form="delete-{{ $image->id }}" class="grid size-7 place-items-center rounded-full bg-white/95 text-red-600 shadow-soft" aria-label="Delete photo"><x-icon name="trash" class="size-3.5" /></button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <label class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-2xl bg-royal-50/70 px-6 py-8 text-center text-sm text-ink/60 transition hover:bg-royal-50">
                    <x-icon name="upload" class="size-6 text-royal-600" />
                    <span x-text="files.length ? files.length + ' photo' + (files.length > 1 ? 's' : '') + ' ready to upload' : 'Add photos (JPG, PNG or WebP, up to 8 MB each)'"></span>
                    <input type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple class="sr-only" @change="files = [...$event.target.files]">
                </label>
                @error('photos')<p class="error">{{ $message }}</p>@enderror
                @error('photos.*')<p class="error">{{ $message }}</p>@enderror
                <div>
                    <span class="label">Video tour upload <span class="font-normal text-ink/40">(optional)</span></span>
                    @if ($property->video_path)
                        <video src="{{ $property->videoFileUrl() }}" controls preload="metadata" class="mb-3 aspect-video w-full rounded-2xl bg-royal-950"></video>
                        <label class="mb-3 flex items-center gap-3 text-sm"><input type="checkbox" name="remove_video" value="1" class="size-4 accent-royal-700"> Remove this video</label>
                    @endif
                    <label class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-2xl bg-royal-50/70 px-6 py-6 text-center text-sm text-ink/60 transition hover:bg-royal-50">
                        <x-icon name="upload" class="size-6 text-royal-600" />
                        <span x-text="video ? video + ' ready to upload' : '{{ $property->video_path ? 'Replace video' : 'Add a video' }} (MP4, MOV or WebM, up to {{ intdiv(\App\Models\Property::VIDEO_MAX_KB, 1024) }} MB)'"></span>
                        <input type="file" name="video" accept="video/mp4,video/quicktime,video/webm" class="sr-only" @change="video = $event.target.files[0]?.name ?? ''">
                    </label>
                    @error('video')<p class="error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label" for="video_url">Video tour link <span class="font-normal text-ink/40">(YouTube, Vimeo or any video page)</span></label>
                    <input id="video_url" name="video_url" type="url" value="{{ old('video_url', $property->video_url) }}" class="input" placeholder="https://youtu.be/...">
                    @error('video_url')<p class="error">{{ $message }}</p>@enderror
                </div>
            </section>
        </div>

        {{-- Side panel --}}
        <div class="space-y-6 xl:sticky xl:top-24 xl:self-start">
            <section class="card space-y-4 p-6">
                <h2 class="font-semibold text-royal-950">Publishing</h2>
                <div>
                    <label class="label" for="status">Status</label>
                    <select id="status" name="status" class="input">
                        <option value="draft" @selected(old('status', $property->status) === 'draft')>Draft (hidden)</option>
                        <option value="published" @selected(old('status', $property->status) === 'published')>Published</option>
                    </select>
                </div>
                <div>
                    <label class="label" for="availability">Availability</label>
                    <select id="availability" name="availability" class="input">
                        @foreach (\App\Models\Property::AVAILABILITY as $key => $label)
                            <option value="{{ $key }}" @selected(old('availability', $property->availability) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="flex items-center gap-3 text-sm"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $property->is_featured)) class="size-4 accent-royal-700"> Feature on the home page</label>
                <div>
                    <label class="label" for="slug">Web address</label>
                    <div class="flex items-center rounded-xl bg-royal-50/60 text-sm">
                        <span class="pl-4 text-ink/40">/properties/</span>
                        <input id="slug" name="slug" value="{{ old('slug', $property->slug) }}" class="input bg-transparent pl-0.5" placeholder="made-from-title">
                    </div>
                    @error('slug')<p class="error">{{ $message }}</p>@enderror
                </div>
                <button class="btn-primary w-full py-3">{{ $editing ? 'Save changes' : 'Create listing' }}</button>
                @if ($editing)
                    <p class="text-center text-xs text-ink/45">Ref {{ $property->reference }} · {{ number_format($property->views) }} views · updated {{ $property->updated_at->diffForHumans() }}</p>
                @endif
            </section>

            @if ($editing)
                <section class="card p-6">
                    <h2 class="font-semibold text-royal-950">More</h2>
                    <div class="mt-4 grid gap-2">
                        <button form="duplicate" class="btn-ghost w-full"><x-icon name="copy" class="size-4" /> Duplicate as draft</button>
                        <button form="delete-property" class="btn-danger w-full"><x-icon name="trash" class="size-4" /> Delete listing</button>
                    </div>
                </section>
            @endif
        </div>
    </form>

    {{-- Secondary forms (kept outside the main form; buttons point at them with form="...") --}}
    @if ($editing)
        <form id="duplicate" method="post" action="{{ route('admin.properties.duplicate', $property) }}">@csrf</form>
        <form id="delete-property" method="post" action="{{ route('admin.properties.destroy', $property) }}" data-confirm="Delete this listing and all its photos? This cannot be undone.">@csrf @method('delete')</form>
        @foreach ($property->images as $image)
            <form id="cover-{{ $image->id }}" method="post" action="{{ route('admin.properties.images.cover', [$property, $image]) }}">@csrf</form>
            <form id="delete-{{ $image->id }}" method="post" action="{{ route('admin.properties.images.destroy', [$property, $image]) }}" data-confirm="Delete this photo?">@csrf @method('delete')</form>
        @endforeach
    @endif
</x-layouts.admin>
