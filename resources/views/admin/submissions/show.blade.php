@php($currency = \App\Models\Property::CURRENCIES[$submission->currency] ?? $submission->currency)
<x-layouts.admin title="Submission from {{ $submission->name }}">
    <div class="grid gap-6 xl:grid-cols-[1fr_22rem]">
        <section class="card p-6 sm:p-8">
            <div class="flex flex-wrap items-center gap-3">
                <x-admin.status :status="$submission->status" :label="\App\Models\Submission::STATUSES[$submission->status]" />
                <span class="text-sm text-ink/55">Received {{ $submission->created_at->format('D j M Y, H:i') }}</span>
            </div>
            <dl class="mt-6 grid grid-cols-2 gap-5 sm:grid-cols-3">
                @foreach ([
                    'Offer' => \App\Models\Property::OFFERS[$submission->offer_type] ?? $submission->offer_type,
                    'Type' => $submission->category?->name ?? 'Not sure',
                    'Location' => $submission->location,
                    'Asking price' => $submission->price ? $currency.' '.number_format((float) $submission->price) : 'Not given',
                    'Bedrooms' => $submission->bedrooms ?? '-',
                    'Bathrooms' => $submission->bathrooms ?? '-',
                ] as $label => $value)
                    <div><dt class="text-xs font-semibold uppercase tracking-wider text-ink/45">{{ $label }}</dt><dd class="mt-1 font-medium">{{ $value }}</dd></div>
                @endforeach
            </dl>
            @if ($submission->description)
                <p class="mt-6 text-xs font-semibold uppercase tracking-wider text-ink/45">Notes from the owner</p>
                <p class="mt-2 whitespace-pre-line text-ink/80">{{ $submission->description }}</p>
            @endif
            @if (! empty($submission->images))
                <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @foreach ($submission->images as $path)
                        <a href="{{ asset('storage/'.$path) }}" target="_blank" class="aspect-[4/3] overflow-hidden rounded-2xl bg-royal-100"><img src="{{ asset('storage/'.$path) }}" alt="" class="size-full object-cover"></a>
                    @endforeach
                </div>
            @endif
            @if ($submission->video_path)
                <video src="{{ asset('storage/'.$submission->video_path) }}" controls preload="metadata" class="mt-6 aspect-video w-full rounded-2xl bg-royal-950"></video>
            @endif
            @if ($submission->video_url)
                <p class="mt-6 text-xs font-semibold uppercase tracking-wider text-ink/45">Video link</p>
                <a href="{{ $submission->video_url }}" target="_blank" rel="noopener nofollow" class="mt-2 inline-block break-all font-medium text-royal-700 hover:text-gold-600">{{ $submission->video_url }}</a>
            @endif
        </section>

        <div class="space-y-6">
            <section class="card p-6">
                <h2 class="font-semibold text-royal-950">Owner</h2>
                <p class="mt-3 font-medium">{{ $submission->name }}</p>
                <p class="text-sm text-ink/60">{{ $submission->phone }}</p>
                @if ($submission->email)<p class="text-sm text-ink/60 break-all">{{ $submission->email }}</p>@endif
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <a href="tel:{{ \App\Models\Setting::tel($submission->phone) }}" class="btn-ghost"><x-icon name="phone" class="size-4" /> Call</a>
                    <a href="{{ \App\Models\Setting::whatsappUrl('Hello '.Str::before($submission->name, ' ').', thank you for sending us your property in '.$submission->location.'.', $submission->phone) }}" target="_blank" rel="noopener" class="btn-ghost"><x-icon name="whatsapp" class="size-4" /> WhatsApp</a>
                </div>
            </section>

            <section class="card space-y-3 p-6">
                <h2 class="font-semibold text-royal-950">Decision</h2>
                @if ($submission->property)
                    <a href="{{ route('admin.properties.edit', $submission->property) }}" class="btn-primary w-full">Open the listing</a>
                @else
                    <form method="post" action="{{ route('admin.submissions.convert', $submission) }}">
                        @csrf
                        <button class="btn-primary w-full">Create draft listing</button>
                    </form>
                    <p class="text-xs text-ink/50">Copies the details and photos into a new draft you can finish and publish.</p>
                @endif
                <form method="post" action="{{ route('admin.submissions.update', $submission) }}" class="flex gap-2">
                    @csrf @method('put')
                    <select name="status" class="input" aria-label="Status">
                        @foreach (\App\Models\Submission::STATUSES as $key => $label)
                            <option value="{{ $key }}" @selected($submission->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="btn-ghost">Save</button>
                </form>
            </section>

            <form method="post" action="{{ route('admin.submissions.destroy', $submission) }}" data-confirm="Delete this submission and its photos?">
                @csrf @method('delete')
                <button class="btn-danger w-full"><x-icon name="trash" class="size-4" /> Delete</button>
            </form>
        </div>
    </div>
</x-layouts.admin>
