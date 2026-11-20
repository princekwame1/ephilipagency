<x-layouts.admin title="Categories">
    <x-slot:actions>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary"><x-icon name="plus" class="size-4" /> New category</a>
    </x-slot:actions>
    <div class="card overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="text-xs uppercase tracking-wider text-ink/45">
                <tr><th class="px-6 py-4 font-semibold">Name</th><th class="py-4 font-semibold">Listings</th><th class="py-4 font-semibold">Order</th><th class="px-6 py-4"></th></tr>
            </thead>
            <tbody>
                @forelse ($categories as $c)
                    <tr class="odd:bg-royal-50/30 hover:bg-royal-50">
                        <td class="px-6 py-3.5"><p class="font-medium">{{ $c->name }}</p><p class="text-xs text-ink/50">{{ $c->description }}</p></td>
                        <td class="py-3.5"><a href="{{ route('admin.properties.index', ['category' => $c->id]) }}" class="text-royal-700 hover:underline">{{ $c->properties_count }}</a></td>
                        <td class="py-3.5 text-ink/60">{{ $c->sort }}</td>
                        <td class="px-6 py-3.5">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.categories.edit', $c) }}" class="btn-ghost btn-sm">Edit</a>
                                <form method="post" action="{{ route('admin.categories.destroy', $c) }}" data-confirm="Delete {{ $c->name }}? Its listings stay, without a category.">@csrf @method('delete')<button class="btn-danger btn-sm">Delete</button></form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-16 text-center text-ink/50">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin>
