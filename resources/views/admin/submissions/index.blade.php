<x-layouts.admin title="Owner submissions">
    <div class="flex gap-2 overflow-x-auto scrollbar-none">
        @foreach (['' => 'All'] + \App\Models\Submission::STATUSES as $key => $label)
            <a href="{{ route('admin.submissions.index', array_filter(['status' => $key ?: null])) }}"
                class="shrink-0 rounded-full px-4 py-2 text-sm font-medium transition {{ request('status', '') === $key ? 'bg-royal-900 text-white' : 'bg-white text-ink/65 shadow-soft hover:text-royal-800' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($submissions as $s)
            <a href="{{ route('admin.submissions.show', $s) }}" class="card group overflow-hidden transition hover:-translate-y-0.5 hover:shadow-lift">
                <div class="aspect-[16/9] bg-royal-100">
                    @if (! empty($s->images))
                        <img src="{{ asset('storage/'.$s->images[0]) }}" alt="" class="size-full object-cover">
                    @else
                        <div class="grid size-full place-items-center text-sm text-ink/40">No photos</div>
                    @endif
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between gap-3">
                        <p class="truncate font-medium text-royal-950">{{ $s->category?->name ?? 'Property' }} in {{ $s->location }}</p>
                        <x-admin.status :status="$s->status" :label="\App\Models\Submission::STATUSES[$s->status]" />
                    </div>
                    <p class="mt-1 text-sm text-ink/55">{{ \App\Models\Property::OFFERS[$s->offer_type] ?? $s->offer_type }} · {{ $s->name }} · {{ $s->created_at->diffForHumans() }}</p>
                </div>
            </a>
        @empty
            <p class="card col-span-full px-6 py-16 text-center text-ink/50">No submissions yet. Owners can send theirs from the "List your property" page.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $submissions->links() }}</div>
</x-layouts.admin>
