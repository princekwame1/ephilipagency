<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.submissions.index', [
            'submissions' => Submission::with('category')
                ->when($request->query('status'), fn ($q, $s) => $q->where('status', $s))
                ->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function show(Submission $submission)
    {
        return view('admin.submissions.show', ['submission' => $submission->load(['category', 'property'])]);
    }

    public function update(Request $request, Submission $submission)
    {
        $submission->update($request->validate(['status' => ['required', Rule::in(array_keys(Submission::STATUSES))]]));

        return back()->with('status', 'Submission marked '.strtolower(Submission::STATUSES[$submission->status]).'.');
    }

    public function destroy(Submission $submission)
    {
        $submission->delete();

        return redirect()->route('admin.submissions.index')->with('status', 'Submission deleted.');
    }

    /** Turn an owner's submission into a draft listing (photos are copied over). */
    public function convert(Submission $submission)
    {
        if ($submission->property) {
            return redirect()->route('admin.properties.edit', $submission->property);
        }

        $offer = $submission->offer_type;
        $property = Property::create([
            'title' => trim(($submission->bedrooms ? $submission->bedrooms.'-bedroom ' : '').($submission->category?->name ?? 'Property').' in '.$submission->location),
            'category_id' => $submission->category_id,
            'offer_type' => $offer,
            'status' => 'draft',
            'price' => $submission->price ?? 0,
            'currency' => $submission->currency,
            'price_period' => match ($offer) { 'rent' => 'month', 'lease' => 'year', 'short_stay' => 'night', default => null },
            'bedrooms' => $submission->bedrooms ?? 0,
            'bathrooms' => $submission->bathrooms ?? 0,
            'location' => $submission->location,
            'description' => $submission->description,
        ]);

        foreach ($submission->images ?? [] as $i => $path) {
            if (Storage::disk('public')->exists($path)) {
                $copy = 'properties/'.basename($path);
                Storage::disk('public')->copy($path, $copy);
                $property->images()->create(['path' => $copy, 'sort' => $i]);
            }
        }

        if ($submission->video_path && Storage::disk('public')->exists($submission->video_path)) {
            $copy = 'properties/videos/'.basename($submission->video_path);
            Storage::disk('public')->copy($submission->video_path, $copy);
            $property->update(['video_path' => $copy]);
        }
        if ($submission->video_url) {
            $property->update(['video_url' => $submission->video_url]);
        }

        $submission->update(['status' => 'approved', 'property_id' => $property->id]);

        return redirect()->route('admin.properties.edit', $property)->with('status', 'Draft listing created from the submission. Check the details, then publish.');
    }
}
