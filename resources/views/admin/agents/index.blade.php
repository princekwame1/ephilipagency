<x-layouts.admin title="Agents">
    <x-slot:actions>
        <a href="{{ route('admin.agents.create') }}" class="btn-primary"><x-icon name="plus" class="size-4" /> New agent</a>
    </x-slot:actions>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($agents as $agent)
            <div class="card flex items-center gap-4 p-5">
                @if ($agent->photoUrl())
                    <img src="{{ $agent->photoUrl() }}" alt="" class="size-14 rounded-full object-cover">
                @else
                    <span class="grid size-14 shrink-0 place-items-center rounded-full bg-royal-800 font-display text-lg text-gold-300">{{ $agent->initials() }}</span>
                @endif
                <div class="min-w-0 flex-1">
                    <p class="truncate font-medium">{{ $agent->name }} @unless ($agent->is_active)<span class="text-xs font-normal text-ink/40">(hidden)</span>@endunless</p>
                    <p class="truncate text-sm text-ink/55">{{ $agent->role }}</p>
                    <p class="text-xs text-ink/45">{{ $agent->properties_count }} {{ Str::plural('listing', $agent->properties_count) }}</p>
                </div>
                <a href="{{ route('admin.agents.edit', $agent) }}" class="btn-ghost btn-sm">Edit</a>
            </div>
        @empty
            <p class="card col-span-full px-6 py-16 text-center text-ink/50">No agents yet. Listings without an agent show the main office contact.</p>
        @endforelse
    </div>
</x-layouts.admin>
