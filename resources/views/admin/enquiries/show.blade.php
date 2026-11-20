@php($first = Str::before($enquiry->name, ' '))
<x-layouts.admin :title="$enquiry->name">
    <div class="grid gap-6 xl:grid-cols-[1fr_22rem]">
        <div class="space-y-6">
            <section class="card p-6 sm:p-8">
                <div class="flex flex-wrap items-center gap-3">
                    <x-admin.status :status="$enquiry->status" :label="$enquiry->statusLabel()" />
                    <span class="text-sm text-ink/55">{{ $enquiry->typeLabel() }} · {{ $enquiry->created_at->format('D j M Y, H:i') }}</span>
                </div>
                @if ($enquiry->preferred_date)
                    <div class="mt-6 rounded-2xl bg-royal-950 p-5 text-white">
                        <p class="text-sm text-white/60">Requested viewing</p>
                        <p class="mt-1 font-display text-2xl text-gold-300">{{ $enquiry->preferred_date->format('l j F') }}</p>
                        <p class="text-sm text-white/80">{{ \App\Models\Enquiry::TIMES[$enquiry->preferred_time] ?? 'Any time' }}</p>
                    </div>
                @endif
                <div class="mt-6">
                    <p class="text-xs font-semibold uppercase tracking-wider text-ink/45">Message</p>
                    <p class="mt-2 whitespace-pre-line text-ink/80">{{ $enquiry->message ?: 'No message.' }}</p>
                </div>
                @if ($enquiry->property)
                    <a href="{{ route('admin.properties.edit', $enquiry->property) }}" class="mt-6 flex items-center gap-4 rounded-2xl bg-royal-50 p-3 hover:bg-royal-100">
                        <img src="{{ $enquiry->property->coverUrl() }}" alt="" class="h-14 w-20 rounded-xl object-cover">
                        <span class="min-w-0">
                            <span class="block truncate font-medium">{{ $enquiry->property->title }}</span>
                            <span class="text-sm text-ink/55">{{ $enquiry->property->reference }} · {{ $enquiry->property->formattedPrice() }} {{ $enquiry->property->periodLabel() }}</span>
                        </span>
                    </a>
                @endif
            </section>

            @if ($history->isNotEmpty())
                <section class="card p-6">
                    <h2 class="font-semibold text-royal-950">Earlier from {{ $first }}</h2>
                    <ul class="mt-3 space-y-1">
                        @foreach ($history as $h)
                            <li><a href="{{ route('admin.enquiries.show', $h) }}" class="flex items-center justify-between gap-4 rounded-xl px-3 py-2.5 text-sm hover:bg-royal-50">
                                <span class="truncate">{{ $h->typeLabel() }}: {{ $h->property?->title ?? Str::limit($h->message, 50) }}</span>
                                <span class="shrink-0 text-xs text-ink/45">{{ $h->created_at->format('j M Y') }}</span>
                            </a></li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>

        <div class="space-y-6">
            <section class="card p-6">
                <h2 class="font-semibold text-royal-950">Contact</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div><dt class="text-ink/45">Phone</dt><dd class="font-medium">{{ $enquiry->phone ?: '-' }}</dd></div>
                    <div><dt class="text-ink/45">Email</dt><dd class="font-medium break-all">{{ $enquiry->email ?: '-' }}</dd></div>
                </dl>
                <div class="mt-5 grid grid-cols-2 gap-2">
                    @if ($enquiry->phone)
                        <a href="tel:{{ \App\Models\Setting::tel($enquiry->phone) }}" class="btn-ghost"><x-icon name="phone" class="size-4" /> Call</a>
                        <a href="{{ \App\Models\Setting::whatsappUrl('Hello '.$first.', thanks for contacting '.\App\Models\Setting::get('business_name').($enquiry->property ? ' about '.$enquiry->property->title : '').'.', $enquiry->phone) }}" target="_blank" rel="noopener" class="btn-ghost"><x-icon name="whatsapp" class="size-4" /> WhatsApp</a>
                    @endif
                    @if ($enquiry->email)
                        <a href="mailto:{{ $enquiry->email }}?subject={{ rawurlencode('Re: '.($enquiry->property?->title ?? 'your enquiry')) }}" class="btn-ghost col-span-2"><x-icon name="mail" class="size-4" /> Email</a>
                    @endif
                </div>
            </section>

            <form method="post" action="{{ route('admin.enquiries.update', $enquiry) }}" class="card space-y-4 p-6">
                @csrf @method('put')
                <h2 class="font-semibold text-royal-950">Follow-up</h2>
                <div>
                    <label class="label" for="status">Status</label>
                    <select id="status" name="status" class="input">
                        @foreach (\App\Models\Enquiry::STATUSES as $key => $label)
                            <option value="{{ $key }}" @selected($enquiry->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($enquiry->type === 'viewing')
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="label" for="preferred_date">Viewing day</label>
                            <input id="preferred_date" name="preferred_date" type="date" value="{{ $enquiry->preferred_date?->toDateString() }}" class="input">
                        </div>
                        <div>
                            <label class="label" for="preferred_time">Time</label>
                            <select id="preferred_time" name="preferred_time" class="input">
                                @foreach (\App\Models\Enquiry::TIMES as $key => $label)
                                    <option value="{{ $key }}" @selected($enquiry->preferred_time === $key)>{{ Str::before($label, ' (') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div>
                    <label class="label" for="notes">Private notes</label>
                    <textarea id="notes" name="notes" rows="4" class="input" placeholder="Called on Monday, wants to view Saturday...">{{ old('notes', $enquiry->notes) }}</textarea>
                </div>
                <button class="btn-primary w-full">Save</button>
            </form>

            <form method="post" action="{{ route('admin.enquiries.destroy', $enquiry) }}" data-confirm="Delete this enquiry?">
                @csrf @method('delete')
                <button class="btn-danger w-full"><x-icon name="trash" class="size-4" /> Delete</button>
            </form>
        </div>
    </div>
</x-layouts.admin>
