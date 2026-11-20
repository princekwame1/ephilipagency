<x-layouts.admin title="Amenities">
    <form method="post" action="{{ route('admin.amenities.store') }}" class="card flex max-w-2xl gap-3 p-4">
        @csrf
        <label for="new-amenity" class="sr-only">New amenity</label>
        <input id="new-amenity" name="name" required class="input" placeholder="Add an amenity, e.g. Swimming pool">
        <button class="btn-primary shrink-0"><x-icon name="plus" class="size-4" /> Add</button>
    </form>
    @error('name')<p class="error">{{ $message }}</p>@enderror

    <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($amenities as $a)
            <div class="card flex items-center gap-2 p-3" x-data="{ edit: false }">
                <form method="post" action="{{ route('admin.amenities.update', $a) }}" class="flex flex-1 items-center gap-2">
                    @csrf @method('put')
                    <span x-show="!edit" class="flex-1 px-2 text-sm font-medium">{{ $a->name }} <span class="font-normal text-ink/40">· {{ $a->properties_count }}</span></span>
                    <input x-show="edit" x-cloak name="name" value="{{ $a->name }}" class="input py-2" aria-label="Amenity name">
                    <button x-show="edit" x-cloak class="btn-primary btn-sm">Save</button>
                </form>
                <button type="button" x-show="!edit" @click="edit = true" class="btn-ghost btn-sm">Rename</button>
                <form method="post" action="{{ route('admin.amenities.destroy', $a) }}" data-confirm="Delete {{ $a->name }}?">@csrf @method('delete')
                    <button class="grid size-8 place-items-center rounded-full text-ink/40 hover:bg-red-50 hover:text-red-600" aria-label="Delete {{ $a->name }}"><x-icon name="trash" class="size-4" /></button>
                </form>
            </div>
        @endforeach
    </div>
</x-layouts.admin>
