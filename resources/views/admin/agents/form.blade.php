<x-layouts.admin :title="$agent->exists ? 'Edit agent' : 'New agent'">
    <form method="post" action="{{ $agent->exists ? route('admin.agents.update', $agent) : route('admin.agents.store') }}" enctype="multipart/form-data" class="card max-w-3xl space-y-4 p-6">
        @csrf
        @if ($agent->exists) @method('put') @endif
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="name">Name</label>
                <input id="name" name="name" value="{{ old('name', $agent->name) }}" required class="input">
                @error('name')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="label" for="role">Role</label>
                <input id="role" name="role" value="{{ old('role', $agent->role) }}" class="input" placeholder="e.g. Lettings agent">
            </div>
            <div>
                <label class="label" for="phone">Phone</label>
                <input id="phone" name="phone" value="{{ old('phone', $agent->phone) }}" class="input">
            </div>
            <div>
                <label class="label" for="whatsapp">WhatsApp</label>
                <input id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $agent->whatsapp) }}" class="input">
            </div>
            <div class="sm:col-span-2">
                <label class="label" for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $agent->email) }}" class="input">
                @error('email')<p class="error">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="label" for="bio">Short bio</label>
            <textarea id="bio" name="bio" rows="3" class="input">{{ old('bio', $agent->bio) }}</textarea>
        </div>
        <div class="flex flex-wrap items-center gap-4">
            @if ($agent->photoUrl())
                <img src="{{ $agent->photoUrl() }}" alt="" class="size-16 rounded-full object-cover">
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remove_photo" value="1" class="accent-royal-700"> Remove photo</label>
            @endif
            <div>
                <label class="label" for="photo">Photo</label>
                <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" class="text-sm file:mr-3 file:rounded-full file:border-0 file:bg-royal-50 file:px-4 file:py-2 file:font-semibold file:text-royal-800">
                @error('photo')<p class="error">{{ $message }}</p>@enderror
            </div>
        </div>
        <label class="flex items-center gap-3 text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $agent->is_active)) class="size-4 accent-royal-700"> Show on the website</label>
        <div class="flex flex-wrap gap-2 pt-2">
            <button class="btn-primary">Save</button>
            <a href="{{ route('admin.agents.index') }}" class="btn-ghost">Cancel</a>
            @if ($agent->exists)
                <button form="delete-agent" class="btn-danger ml-auto">Delete</button>
            @endif
        </div>
    </form>
    @if ($agent->exists)
        <form id="delete-agent" method="post" action="{{ route('admin.agents.destroy', $agent) }}" data-confirm="Remove {{ $agent->name }}?">@csrf @method('delete')</form>
    @endif
</x-layouts.admin>
