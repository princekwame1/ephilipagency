<x-layouts.admin :title="$post->exists ? 'Edit article' : 'New article'">
    <form method="post" action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}" enctype="multipart/form-data" class="grid gap-6 xl:grid-cols-[1fr_20rem]">
        @csrf
        @if ($post->exists) @method('put') @endif
        <section class="card space-y-4 p-6">
            <div>
                <label class="label" for="title">Title</label>
                <input id="title" name="title" value="{{ old('title', $post->title) }}" required class="input text-base">
                @error('title')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="label" for="excerpt">Intro <span class="font-normal text-ink/40">(one or two sentences)</span></label>
                <textarea id="excerpt" name="excerpt" rows="2" maxlength="300" class="input">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>
            <div>
                <label class="label" for="body">Article</label>
                <textarea id="body" name="body" rows="20" required class="input font-mono text-[13px] leading-6">{{ old('body', $post->body) }}</textarea>
                <p class="mt-1.5 text-xs text-ink/45">Formatting: ## heading, **bold**, "- " bullet points, [link text](https://...), blank line between paragraphs.</p>
                @error('body')<p class="error">{{ $message }}</p>@enderror
            </div>
        </section>
        <section class="card space-y-4 self-start p-6">
            <label class="flex items-center gap-3 text-sm"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published)) class="size-4 accent-royal-700"> Published</label>
            <div>
                <label class="label" for="published_at">Publish date</label>
                <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}" class="input">
                <p class="mt-1.5 text-xs text-ink/45">A future date schedules the article.</p>
            </div>
            <div>
                <label class="label" for="slug">Web address</label>
                <input id="slug" name="slug" value="{{ old('slug', $post->slug) }}" class="input" placeholder="made-from-title">
                @error('slug')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div>
                <span class="label">Cover image</span>
                @if ($post->coverUrl())<img src="{{ $post->coverUrl() }}" alt="" class="mb-3 aspect-[16/10] w-full rounded-2xl object-cover">@endif
                <input name="cover" type="file" accept="image/jpeg,image/png,image/webp" class="text-sm file:mr-3 file:rounded-full file:border-0 file:bg-royal-50 file:px-4 file:py-2 file:font-semibold file:text-royal-800" aria-label="Cover image">
                @error('cover')<p class="error">{{ $message }}</p>@enderror
            </div>
            <button class="btn-primary w-full">Save</button>
        </section>
    </form>
</x-layouts.admin>
