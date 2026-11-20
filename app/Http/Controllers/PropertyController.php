<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Property;
use App\Support\LeadNotifier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'offer' => ['nullable', Rule::in(array_keys(Property::OFFERS))],
            'category' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:100'],
            'beds' => ['nullable', 'integer', 'between:0,20'],
            'baths' => ['nullable', 'integer', 'between:0,20'],
            'min' => ['nullable', 'numeric', 'min:0'],
            'max' => ['nullable', 'numeric', 'min:0'],
            'furnished' => ['nullable', 'boolean'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['integer'],
            'sort' => ['nullable', Rule::in(array_keys(Property::SORTS))],
            'view' => ['nullable', Rule::in(['grid', 'list', 'map'])],
        ]);

        $properties = Property::published()
            ->with(['cover', 'category'])
            ->filter($filters)
            ->paginate(12)
            ->withQueryString();

        $activeFilters = collect($filters)->except(['sort', 'view'])->filter(fn ($v) => filled($v))->count();

        return view('site.properties.index', [
            'properties' => $properties,
            'filters' => $filters,
            'activeFilters' => $activeFilters,
            'categories' => Category::orderBy('sort')->orderBy('name')->get(),
            'amenities' => Amenity::orderBy('name')->get(),
            'locations' => Property::published()->distinct()->orderBy('location')->pluck('location'),
            'category' => isset($filters['category']) ? Category::where('slug', $filters['category'])->first() : null,
        ]);
    }

    public function show(Property $property)
    {
        abort_unless($property->status === 'published' || auth()->check(), 404);

        // Count one view per visitor session per listing.
        $seen = session('viewed', []);
        if (! in_array($property->id, $seen, true) && ! auth()->check()) {
            $property->increment('views');
            session()->push('viewed', $property->id);
        }

        $property->load(['images', 'amenities', 'category', 'agent']);

        $similar = Property::published()
            ->with(['cover', 'category'])
            ->whereKeyNot($property->id)
            ->where(fn ($q) => $q->where('category_id', $property->category_id)->orWhere('location', $property->location))
            ->orderByRaw('offer_type = ? desc', [$property->offer_type])
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('site.properties.show', compact('property', 'similar'));
    }

    /** Favourites are kept in the visitor's browser; this page renders the saved IDs. */
    public function saved(Request $request)
    {
        $ids = collect(explode(',', (string) $request->query('ids')))->map(fn ($id) => (int) $id)->filter()->take(50);

        $properties = $ids->isEmpty() ? collect() : Property::published()->with(['cover', 'category'])->whereKey($ids)->get()
            ->sortBy(fn ($p) => $ids->search($p->id))->values();

        return view('site.properties.saved', compact('properties'));
    }

    public function enquire(Request $request, Property $property)
    {
        abort_unless($property->status === 'published', 404);

        $data = $request->validate([
            'type' => ['required', Rule::in(['enquiry', 'viewing'])],
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'message' => ['nullable', 'string', 'max:3000'],
            'preferred_date' => ['nullable', 'required_if:type,viewing', 'date', 'after_or_equal:today', 'before:+6 months'],
            'preferred_time' => ['nullable', Rule::in(array_keys(Enquiry::TIMES))],
            'website' => ['prohibited'], // honeypot
        ], [
            'preferred_date.required_if' => 'Pick a day for the viewing.',
            'website.prohibited' => 'Something went wrong. Please try again.',
        ]);

        if ($data['type'] === 'enquiry') {
            $data['preferred_date'] = $data['preferred_time'] = null;
        }

        $enquiry = $property->enquiries()->create($data);
        LeadNotifier::enquiry($enquiry);

        return back()->with('sent', site($data['type'] === 'viewing' ? 'property_viewing_success' : 'property_question_success', ['name' => strtok($data['name'], ' ')]));
    }
}
