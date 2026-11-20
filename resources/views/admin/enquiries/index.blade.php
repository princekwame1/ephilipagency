<x-layouts.admin title="Enquiries">
    <x-slot:actions>
        <a href="{{ route('admin.enquiries.export', request()->query()) }}" class="btn-ghost bg-white"><x-icon name="download" class="size-4" /> Export CSV</a>
    </x-slot:actions>

    <div class="flex gap-2 overflow-x-auto scrollbar-none">
        @foreach (['' => 'All'] + \App\Models\Enquiry::STATUSES as $key => $label)
            @php($active = request('status', '') === $key)
            <a href="{{ route('admin.enquiries.index', array_filter(['status' => $key ?: null, 'type' => request('type'), 'q' => request('q')])) }}"
                class="shrink-0 rounded-full px-4 py-2 text-sm font-medium transition {{ $active ? 'bg-royal-900 text-white' : 'bg-white text-ink/65 shadow-soft hover:text-royal-800' }}">
                {{ $label }} @if ($key && ($counts[$key] ?? 0))<span class="ml-1 {{ $active ? 'text-gold-300' : 'text-ink/40' }}">{{ $counts[$key] }}</span>@endif
            </a>
        @endforeach
    </div>

    <form method="get" class="mt-4 flex flex-wrap gap-3">
        @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        <div class="relative min-w-60 flex-1">
            <x-icon name="search" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-ink/40" />
            <input name="q" value="{{ request('q') }}" class="input bg-white pl-10 shadow-soft" placeholder="Search name, phone or email" aria-label="Search">
        </div>
        <select name="type" class="input w-auto bg-white shadow-soft" data-autosubmit aria-label="Type">
            <option value="">All types</option>
            @foreach (\App\Models\Enquiry::TYPES as $key => $label)
                <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </form>

    <div class="card mt-6 overflow-x-auto">
        <table class="w-full min-w-[48rem] text-left text-sm">
            <thead class="text-xs uppercase tracking-wider text-ink/45">
                <tr>
                    <th class="px-6 py-4 font-semibold">From</th>
                    <th class="py-4 font-semibold">About</th>
                    <th class="py-4 font-semibold">Viewing</th>
                    <th class="py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 text-right font-semibold">Received</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($enquiries as $e)
                    <tr class="cursor-pointer transition odd:bg-royal-50/30 hover:bg-royal-50 {{ $e->status === 'new' ? 'font-medium' : '' }}" onclick="location.href='{{ route('admin.enquiries.show', $e) }}'">
                        <td class="px-6 py-3.5">
                            <a href="{{ route('admin.enquiries.show', $e) }}" class="text-ink">{{ $e->name }}</a>
                            <p class="text-xs font-normal text-ink/50">{{ $e->phone }}{{ $e->email ? ' · '.$e->email : '' }}</p>
                        </td>
                        <td class="py-3.5">
                            <p class="font-normal text-ink/70">{{ $e->typeLabel() }}</p>
                            <p class="max-w-64 truncate text-xs font-normal text-ink/45">{{ $e->property?->title ?? Str::limit($e->message, 60) }}</p>
                        </td>
                        <td class="py-3.5 font-normal text-ink/65">{{ $e->preferred_date ? $e->preferred_date->format('D j M').' · '.ucfirst($e->preferred_time ?? '') : '-' }}</td>
                        <td class="py-3.5"><x-admin.status :status="$e->status" :label="$e->statusLabel()" /></td>
                        <td class="px-6 py-3.5 text-right text-xs font-normal text-ink/50">{{ $e->created_at->format('j M, H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-16 text-center text-ink/50">No enquiries here.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $enquiries->links() }}</div>
</x-layouts.admin>
