<x-layouts.admin title="Journal">
    <x-slot:actions>
        <a href="{{ route('admin.posts.create') }}" class="btn-primary"><x-icon name="plus" class="size-4" /> New article</a>
    </x-slot:actions>
    <div class="card overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="text-xs uppercase tracking-wider text-ink/45">
                <tr><th class="px-6 py-4 font-semibold">Title</th><th class="py-4 font-semibold">Status</th><th class="py-4 font-semibold">Date</th><th class="px-6 py-4"></th></tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr class="odd:bg-royal-50/30 hover:bg-royal-50">
                        <td class="px-6 py-3.5"><a href="{{ route('admin.posts.edit', $post) }}" class="font-medium hover:text-royal-700">{{ $post->title }}</a></td>
                        <td class="py-3.5"><x-admin.status :status="$post->is_published ? 'published' : 'draft'" /></td>
                        <td class="py-3.5 text-ink/60">{{ $post->published_at?->format('j M Y') }}</td>
                        <td class="px-6 py-3.5">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="btn-ghost btn-sm">Edit</a>
                                <form method="post" action="{{ route('admin.posts.destroy', $post) }}" data-confirm="Delete this article?">@csrf @method('delete')<button class="btn-danger btn-sm">Delete</button></form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-16 text-center text-ink/50">No articles yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $posts->links() }}</div>
</x-layouts.admin>
