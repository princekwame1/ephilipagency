<x-layouts.admin title="Properties">
    <x-slot:actions>
        <a href="{{ route('admin.properties.create') }}" class="btn-primary"><x-icon name="plus" class="size-4" /> New listing</a>
    </x-slot:actions>

    <form method="get" class="flex flex-wrap gap-3">
        <div class="relative min-w-60 flex-1">
            <x-icon name="search" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-ink/40" />
            <input name="q" value="{{ request('q') }}" class="input bg-white pl-10 shadow-soft" placeholder="Search title, reference or area" aria-label="Search">
        </div>
        <select name="status" class="input w-auto bg-white shadow-soft" data-autosubmit aria-label="Status">
            <option value="">All statuses</option>
            <option value="published" @selected(request('status') === 'published')>Published</option>
            <option value="draft" @selected(request('status') === 'draft')>Drafts</option>
        </select>
        <select name="offer" class="input w-auto bg-white shadow-soft" data-autosubmit aria-label="Offer">
            <option value="">All offers</option>
            @foreach (\App\Models\Property::OFFERS as $key => $label)
                <option value="{{ $key }}" @selected(request('offer') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="category" class="input w-auto bg-white shadow-soft" data-autosubmit aria-label="Category">
            <option value="">All types</option>
            @foreach ($categories as $c)
                <option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
    </form>

    <div class="card mt-6 overflow-x-auto">
        <table class="w-full min-w-[56rem] text-left text-sm">
            <thead class="text-xs uppercase tracking-wider text-ink/45">
                <tr>
                    <th class="px-6 py-4 font-semibold">Listing</th>
                    <th class="py-4 font-semibold">Price</th>
                    <th class="py-4 font-semibold">Status</th>
                    <th class="py-4 font-semibold">Availability</th>
                    <th class="py-4 text-right font-semibold">Views · Leads</th>
                    <th class="px-6 py-4"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($properties as $p)
                    <tr class="transition odd:bg-royal-50/30 hover:bg-royal-50">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-4">
                                <img src="{{ $p->coverUrl() }}" alt="" class="h-12 w-16 shrink-0 rounded-xl object-cover">
                                <div class="min-w-0">
                                    <a href="{{ route('admin.properties.edit', $p) }}" class="block max-w-xs truncate font-medium text-ink hover:text-royal-700">{{ $p->title }}</a>
                                    <p class="text-xs text-ink/50">{{ $p->reference }} · {{ $p->category?->name ?? 'Uncategorised' }} · {{ $p->location }}</p>
                                </div>
                                @if ($p->is_featured)<span class="chip-gold shrink-0">Featured</span>@endif
                            </div>
                        </td>
                        <td class="py-3">
                            <p class="font-medium">{{ $p->formattedPrice() }}</p>
                            <p class="text-xs text-ink/50">{{ $p->offerLabel() }} {{ $p->periodLabel() }}</p>
                        </td>
                        <td class="py-3">
                            <form method="post" action="{{ route('admin.properties.quick', $p) }}">
                                @csrf @method('patch')
                                <input type="hidden" name="status" value="{{ $p->status === 'published' ? 'draft' : 'published' }}">
                                <button title="{{ $p->status === 'published' ? 'Unpublish' : 'Publish' }}" class="cursor-pointer"><x-admin.status :status="$p->status" /></button>
                            </form>
                        </td>
                        <td class="py-3">
                            <form method="post" action="{{ route('admin.properties.quick', $p) }}">
                                @csrf @method('patch')
                                <select name="availability" class="rounded-lg bg-transparent py-1 text-sm hover:bg-white focus:outline-none" data-autosubmit aria-label="Availability">
                                    @foreach (\App\Models\Property::AVAILABILITY as $key => $label)
                                        <option value="{{ $key }}" @selected($p->availability === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="py-3 text-right text-ink/60">{{ number_format($p->views) }} · {{ $p->enquiries_count }}</td>
                        <td class="px-6 py-3">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('properties.show', $p) }}" target="_blank" class="grid size-9 place-items-center rounded-full text-ink/50 hover:bg-white hover:text-royal-700" title="View on site"><x-icon name="eye" class="size-4" /></a>
                                <form method="post" action="{{ route('admin.properties.quick', $p) }}">
                                    @csrf @method('patch')
                                    <input type="hidden" name="is_featured" value="{{ $p->is_featured ? 0 : 1 }}">
                                    <button class="grid size-9 place-items-center rounded-full hover:bg-white {{ $p->is_featured ? 'text-gold-500' : 'text-ink/50 hover:text-gold-600' }}" title="{{ $p->is_featured ? 'Remove from featured' : 'Feature on home page' }}"><x-icon name="star" class="size-4 {{ $p->is_featured ? 'fill-current' : '' }}" /></button>
                                </form>
                                <form method="post" action="{{ route('admin.properties.duplicate', $p) }}">
                                    @csrf
                                    <button class="grid size-9 place-items-center rounded-full text-ink/50 hover:bg-white hover:text-royal-700" title="Duplicate"><x-icon name="copy" class="size-4" /></button>
                                </form>
                                <a href="{{ route('admin.properties.edit', $p) }}" class="grid size-9 place-items-center rounded-full text-ink/50 hover:bg-white hover:text-royal-700" title="Edit"><x-icon name="pen" class="size-4" /></a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-16 text-center text-ink/50">No listings match. <a href="{{ route('admin.properties.create') }}" class="font-medium text-royal-700">Add one</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $properties->links() }}</div>
</x-layouts.admin>
