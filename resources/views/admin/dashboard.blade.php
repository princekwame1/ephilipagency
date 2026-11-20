@php
    $max = max(1, $chart->max('total'));
    $total30 = $chart->sum('total');
@endphp
<x-layouts.admin title="Dashboard">
    <x-slot:actions>
        <a href="{{ route('admin.properties.create') }}" class="btn-primary"><x-icon name="plus" class="size-4" /> New listing</a>
    </x-slot:actions>

    <p class="text-sm text-ink/55">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ Str::before(auth()->user()->name, ' ') }}. Here is how things look today.</p>

    {{-- Stats --}}
    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['Live listings', $stats['published'], $stats['available'].' available · '.$stats['drafts'].' drafts', route('admin.properties.index', ['status' => 'published'])],
            ['New leads', $stats['newLeads'], $stats['leadsMonth'].' this month', route('admin.enquiries.index', ['status' => 'new'])],
            ['Listing views', number_format($stats['views']), 'All time', route('admin.properties.index')],
            ['Owner submissions', $stats['pendingSubmissions'], 'Waiting for review', route('admin.submissions.index', ['status' => 'pending'])],
        ] as $i => [$label, $value, $hint, $href])
            <a href="{{ $href }}" class="rounded-3xl p-6 shadow-soft transition hover:-translate-y-0.5 hover:shadow-lift {{ $i === 0 ? 'bg-royal-900 text-white' : 'bg-white' }}">
                <p class="text-sm {{ $i === 0 ? 'text-white/60' : 'text-ink/55' }}">{{ $label }}</p>
                <p class="mt-2 font-display text-4xl {{ $i === 0 ? 'text-gold-300' : 'text-royal-950' }}">{{ $value }}</p>
                <p class="mt-1 text-xs {{ $i === 0 ? 'text-white/50' : 'text-ink/45' }}">{{ $hint }}</p>
            </a>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1.6fr_1fr]">
        {{-- Leads chart --}}
        <section class="card p-6">
            <div class="flex items-baseline justify-between">
                <h2 class="font-semibold text-royal-950">Leads, last 30 days</h2>
                <p class="text-sm text-ink/50">{{ $total30 }} total</p>
            </div>
            <div class="mt-6 flex h-48 items-end gap-1" role="img" aria-label="Leads per day for the last 30 days, {{ $total30 }} in total">
                @foreach ($chart as $day)
                    <div class="group relative flex h-full flex-1 items-end">
                        <div class="w-full rounded-t-md transition {{ $day['date']->isToday() ? 'bg-gold-400' : 'bg-royal-200 group-hover:bg-royal-500' }}"
                            style="height: {{ $day['total'] ? max(4, $day['total'] / $max * 100) : 2 }}%"></div>
                        <span class="pointer-events-none absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 whitespace-nowrap rounded-lg bg-royal-950 px-2.5 py-1 text-xs text-white group-hover:block">
                            {{ $day['date']->format('D j M') }}: {{ $day['total'] }}
                        </span>
                    </div>
                @endforeach
            </div>
            <div class="mt-2 flex justify-between text-xs text-ink/40">
                <span>{{ $chart->first()['date']->format('j M') }}</span><span>Today</span>
            </div>
        </section>

        {{-- Upcoming viewings --}}
        <section class="card p-6">
            <div class="flex items-baseline justify-between">
                <h2 class="font-semibold text-royal-950">Upcoming viewings</h2>
                <a href="{{ route('admin.enquiries.index', ['type' => 'viewing']) }}" class="text-sm font-medium text-royal-700 hover:text-gold-600">All</a>
            </div>
            <ul class="mt-4 space-y-1">
                @forelse ($upcomingViewings as $v)
                    <li>
                        <a href="{{ route('admin.enquiries.show', $v) }}" class="flex items-center gap-4 rounded-2xl px-3 py-2.5 hover:bg-royal-50">
                            <span class="w-12 shrink-0 rounded-xl bg-royal-50 py-1.5 text-center">
                                <span class="block text-[10px] font-semibold uppercase text-gold-600">{{ $v->preferred_date->format('M') }}</span>
                                <span class="block font-display text-lg leading-none text-royal-950">{{ $v->preferred_date->format('j') }}</span>
                            </span>
                            <span class="min-w-0">
                                <span class="block truncate text-sm font-medium">{{ $v->name }} <span class="font-normal text-ink/45">· {{ ucfirst($v->preferred_time ?? '') }}</span></span>
                                <span class="block truncate text-xs text-ink/50">{{ $v->property?->title ?? 'Property removed' }}</span>
                            </span>
                            @if ($v->status === 'scheduled')<span class="chip-gold ml-auto shrink-0">Booked</span>@endif
                        </a>
                    </li>
                @empty
                    <li class="px-3 py-8 text-center text-sm text-ink/50">No viewings requested yet.</li>
                @endforelse
            </ul>
        </section>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1.6fr_1fr]">
        {{-- Recent leads --}}
        <section class="card p-6">
            <div class="flex items-baseline justify-between">
                <h2 class="font-semibold text-royal-950">Recent leads</h2>
                <a href="{{ route('admin.enquiries.index') }}" class="text-sm font-medium text-royal-700 hover:text-gold-600">Open inbox</a>
            </div>
            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <tbody>
                        @forelse ($recentLeads as $lead)
                            <tr class="group">
                                <td class="rounded-l-2xl py-3 pl-3 group-hover:bg-royal-50">
                                    <a href="{{ route('admin.enquiries.show', $lead) }}" class="font-medium text-ink">{{ $lead->name }}</a>
                                    <p class="text-xs text-ink/50">{{ $lead->phone }}</p>
                                </td>
                                <td class="py-3 group-hover:bg-royal-50">
                                    <p class="text-ink/70">{{ $lead->typeLabel() }}</p>
                                    <p class="max-w-56 truncate text-xs text-ink/45">{{ $lead->property?->title ?? 'General' }}</p>
                                </td>
                                <td class="py-3 group-hover:bg-royal-50"><x-admin.status :status="$lead->status" :label="$lead->statusLabel()" /></td>
                                <td class="rounded-r-2xl py-3 pr-3 text-right text-xs text-ink/45 group-hover:bg-royal-50">{{ $lead->created_at->diffForHumans(short: true) }}</td>
                            </tr>
                        @empty
                            <tr><td class="py-8 text-center text-ink/50">No leads yet. They will appear here as soon as someone gets in touch.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Top listings --}}
        <section class="card p-6">
            <h2 class="font-semibold text-royal-950">Most viewed listings</h2>
            <ul class="mt-4 space-y-1">
                @forelse ($topProperties as $p)
                    <li>
                        <a href="{{ route('admin.properties.edit', $p) }}" class="flex items-center gap-3 rounded-2xl p-2 hover:bg-royal-50">
                            <img src="{{ $p->coverUrl() }}" alt="" class="size-12 shrink-0 rounded-xl object-cover">
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-medium">{{ $p->title }}</span>
                                <span class="block text-xs text-ink/50">{{ number_format($p->views) }} views · {{ $p->enquiries_count }} {{ Str::plural('lead', $p->enquiries_count) }}</span>
                            </span>
                        </a>
                    </li>
                @empty
                    <li class="py-8 text-center text-sm text-ink/50">No published listings yet.</li>
                @endforelse
            </ul>
            @if ($byOffer->isNotEmpty())
                <div class="mt-6 flex h-2 overflow-hidden rounded-full">
                    @php($colors = ['rent' => 'bg-royal-700', 'sale' => 'bg-gold-400', 'lease' => 'bg-royal-300', 'short_stay' => 'bg-gold-200'])
                    @foreach ($byOffer as $offer => $n)
                        <div class="{{ $colors[$offer] ?? 'bg-royal-100' }}" style="width: {{ $n / $byOffer->sum() * 100 }}%"></div>
                    @endforeach
                </div>
                <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-ink/60">
                    @foreach ($byOffer as $offer => $n)
                        <span class="flex items-center gap-1.5"><span class="size-2 rounded-full {{ $colors[$offer] ?? 'bg-royal-100' }}"></span>{{ \App\Models\Property::OFFERS[$offer] ?? $offer }} {{ $n }}</span>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</x-layouts.admin>
