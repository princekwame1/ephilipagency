<x-layouts.admin :title="$category->exists ? 'Edit category' : 'New category'">
    <form method="post" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="card max-w-2xl space-y-4 p-6">
        @csrf
        @if ($category->exists) @method('put') @endif
        <div>
            <label class="label" for="name">Name</label>
            <input id="name" name="name" value="{{ old('name', $category->name) }}" required class="input" placeholder="e.g. Apartment">
            @error('name')<p class="error">{{ $message }}</p>@enderror
            @error('slug')<p class="error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="label" for="description">Short description <span class="font-normal text-ink/40">(optional)</span></label>
            <input id="description" name="description" value="{{ old('description', $category->description) }}" class="input">
        </div>
        <div class="max-w-40">
            <label class="label" for="sort">Order</label>
            <input id="sort" name="sort" type="number" min="0" value="{{ old('sort', $category->sort) }}" class="input">
        </div>
        <div class="flex gap-2 pt-2">
            <button class="btn-primary">Save</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-ghost">Cancel</a>
        </div>
    </form>
</x-layouts.admin>
