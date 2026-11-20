<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Amenity;
use App\Models\Category;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $properties = Property::with(['cover', 'category'])
            ->withCount('enquiries')
            ->when($request->query('q'), fn ($q, $term) => $q->where(fn ($w) => $w
                ->where('title', 'like', "%{$term}%")
                ->orWhere('reference', 'like', "%{$term}%")
                ->orWhere('location', 'like', "%{$term}%")))
            ->when($request->query('status'), fn ($q, $s) => $q->where('status', $s))
            ->when($request->query('offer'), fn ($q, $o) => $q->where('offer_type', $o))
            ->when($request->query('category'), fn ($q, $c) => $q->where('category_id', $c))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.properties.index', [
            'properties' => $properties,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return $this->form(new Property([
            'status' => 'draft', 'availability' => 'available', 'offer_type' => 'rent',
            'currency' => 'GHS', 'price_period' => 'month', 'city' => 'Accra',
        ]));
    }

    public function store(Request $request)
    {
        $property = Property::create($this->validated($request));
        $this->syncExtras($request, $property);

        return redirect()->route('admin.properties.edit', $property)->with('status', 'Listing created. Add more photos or publish it when ready.');
    }

    public function edit(Property $property)
    {
        return $this->form($property->load(['images', 'amenities']));
    }

    public function update(Request $request, Property $property)
    {
        $property->update($this->validated($request, $property));
        $this->syncExtras($request, $property);

        return back()->with('status', 'Listing saved.');
    }

    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()->route('admin.properties.index')->with('status', 'Listing deleted.');
    }

    /** Copy a listing (without photos or uploaded video) as a new draft, for similar units in the same building. */
    public function duplicate(Property $property)
    {
        $copy = $property->replicate(['slug', 'reference', 'views', 'published_at', 'video_path']);
        $copy->title = $property->title.' (copy)';
        $copy->status = 'draft';
        $copy->is_featured = false;
        $copy->save();
        $copy->amenities()->sync($property->amenities->pluck('id'));

        return redirect()->route('admin.properties.edit', $copy)->with('status', 'Copied as a draft. Update the details and add photos.');
    }

    /** One-click changes from the listings table: publish/unpublish, feature, availability. */
    public function quick(Request $request, Property $property)
    {
        $data = $request->validate([
            'status' => ['sometimes', Rule::in(['draft', 'published'])],
            'is_featured' => ['sometimes', 'boolean'],
            'availability' => ['sometimes', Rule::in(array_keys(Property::AVAILABILITY))],
        ]);
        if (($data['status'] ?? null) === 'published' && ! $property->published_at) {
            $data['published_at'] = now();
        }
        $property->update($data);

        return back()->with('status', 'Updated "'.$property->title.'".');
    }

    public function makeCover(Property $property, PropertyImage $image)
    {
        abort_unless($image->property_id === $property->id, 404);
        $property->images()->whereKeyNot($image->id)->increment('sort');
        $image->update(['sort' => 0]);
        $property->images()->orderBy('sort')->orderBy('id')->get()->each(fn ($img, $i) => $img->update(['sort' => $i]));

        return back()->with('status', 'Cover photo updated.');
    }

    public function destroyImage(Property $property, PropertyImage $image)
    {
        abort_unless($image->property_id === $property->id, 404);
        $image->delete();

        return back()->with('status', 'Photo removed.');
    }

    private function form(Property $property)
    {
        return view('admin.properties.form', [
            'property' => $property,
            'categories' => Category::orderBy('sort')->orderBy('name')->get(),
            'agents' => Agent::where('is_active', true)->orderBy('name')->get(),
            'amenities' => Amenity::orderBy('name')->get(),
            'locations' => Property::distinct()->orderBy('location')->pluck('location'),
        ]);
    }

    private function validated(Request $request, ?Property $property = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:180', 'alpha_dash', Rule::unique('properties', 'slug')->ignore($property)],
            'category_id' => ['nullable', 'exists:categories,id'],
            'agent_id' => ['nullable', 'exists:agents,id'],
            'offer_type' => ['required', Rule::in(array_keys(Property::OFFERS))],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'availability' => ['required', Rule::in(array_keys(Property::AVAILABILITY))],
            'price' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'currency' => ['required', Rule::in(array_keys(Property::CURRENCIES))],
            'price_period' => ['nullable', Rule::in(array_keys(Property::PERIODS))],
            'bedrooms' => ['required', 'integer', 'between:0,50'],
            'bathrooms' => ['required', 'integer', 'between:0,50'],
            'area' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'parking' => ['nullable', 'integer', 'between:0,100'],
            'location' => ['required', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'summary' => ['nullable', 'string', 'max:300'],
            'description' => ['nullable', 'string', 'max:20000'],
            'video_url' => ['nullable', 'url:http,https', 'max:255'],
            'photos' => ['nullable', 'array', 'max:30'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'video' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm', 'max:'.Property::VIDEO_MAX_KB],
            'remove_video' => ['nullable', 'boolean'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['integer', 'exists:amenities,id'],
        ]);

        $data['furnished'] = $request->boolean('furnished');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['parking'] = (int) ($data['parking'] ?? 0);
        $data['slug'] = filled($data['slug'] ?? null)
            ? Property::uniqueSlug($data['slug'], $property?->id)
            : ($property?->slug ?? Property::uniqueSlug($data['title']));
        if ($data['status'] === 'published' && ! $property?->published_at) {
            $data['published_at'] = now();
        }
        unset($data['photos'], $data['amenities'], $data['video'], $data['remove_video']);

        return $data;
    }

    private function syncExtras(Request $request, Property $property): void
    {
        $property->amenities()->sync($request->input('amenities', []));

        $sort = (int) $property->images()->max('sort');
        foreach ($request->file('photos', []) as $file) {
            $property->images()->create(['path' => $file->store('properties', 'public'), 'sort' => ++$sort]);
        }

        // A new upload replaces the old video; the checkbox removes it.
        $old = $property->video_path;
        if ($request->hasFile('video')) {
            $property->update(['video_path' => $request->file('video')->store('properties/videos', 'public')]);
        } elseif ($request->boolean('remove_video')) {
            $property->update(['video_path' => null]);
        }
        if ($old && $old !== $property->video_path) {
            Storage::disk('public')->delete($old);
        }
    }
}
