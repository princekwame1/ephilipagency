<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Post;
use App\Models\Property;
use App\Models\Submission;
use App\Models\Testimonial;
use App\Support\LeadNotifier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SiteController extends Controller
{
    public function home()
    {
        $published = Property::published();

        return view('site.home', [
            'featured' => (clone $published)->with(['cover', 'category'])->where('is_featured', true)->latest('published_at')->take(6)->get(),
            'latest' => (clone $published)->with(['cover', 'category'])->latest('published_at')->take(8)->get(),
            'categories' => Category::withCount(['properties' => fn ($q) => $q->published()])->orderBy('sort')->get(),
            'locations' => (clone $published)->selectRaw('location, count(*) as total')->groupBy('location')->orderByDesc('total')->take(8)->get(),
            'offerCounts' => (clone $published)->selectRaw('offer_type, count(*) as total')->groupBy('offer_type')->pluck('total', 'offer_type'),
            'testimonials' => Testimonial::where('is_published', true)->orderBy('sort')->take(6)->get(),
            'totalListings' => (clone $published)->count(),
        ]);
    }

    public function about()
    {
        return view('site.about', [
            'testimonials' => Testimonial::where('is_published', true)->orderBy('sort')->take(3)->get(),
            'totalListings' => Property::published()->count(),
            'locationsCount' => Property::published()->distinct()->count('location'),
        ]);
    }

    public function contact()
    {
        return view('site.contact');
    }

    public function sendContact(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'message' => ['required', 'string', 'max:3000'],
            'website' => ['prohibited'],
        ]);

        $enquiry = Enquiry::create($data + ['type' => 'contact']);
        LeadNotifier::enquiry($enquiry);

        return back()->with('sent', site('contact_success', ['name' => strtok($data['name'], ' ')]));
    }

    public function blog()
    {
        return view('site.blog', ['posts' => Post::live()->paginate(9)]);
    }

    public function post(Post $post)
    {
        abort_unless($post->is_published && $post->published_at?->isPast(), 404);

        return view('site.post', [
            'post' => $post,
            'more' => Post::live()->whereKeyNot($post->id)->take(3)->get(),
        ]);
    }

    /** Public page where clients leave a review; it waits for approval in Admin → Reviews. */
    public function review()
    {
        return view('site.review');
    }

    public function storeReview(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'role' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'quote' => ['required', 'string', 'min:10', 'max:1000'],
            'website' => ['prohibited'],
        ], [
            'rating.required' => 'Please choose a star rating.',
            'quote.required' => 'Please write a few words about your experience.',
            'quote.min' => 'Please write a little more (at least 10 characters).',
        ]);
        unset($data['website']);

        Testimonial::create($data + ['is_published' => false, 'source' => 'website', 'sort' => 0]);

        return redirect()->route('review')->with('sent', site('review_success', ['name' => strtok($data['name'], ' ')]));
    }

    public function listProperty()
    {
        return view('site.submit', ['categories' => Category::orderBy('sort')->get()]);
    }

    public function storeSubmission(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'offer_type' => ['required', Rule::in(array_keys(Property::OFFERS))],
            'category_id' => ['nullable', 'exists:categories,id'],
            'location' => ['required', 'string', 'max:120'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:999999999'],
            'currency' => ['required', Rule::in(array_keys(Property::CURRENCIES))],
            'bedrooms' => ['nullable', 'integer', 'between:0,30'],
            'bathrooms' => ['nullable', 'integer', 'between:0,30'],
            'description' => ['nullable', 'string', 'max:5000'],
            'photos' => ['nullable', 'array', 'max:8'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'video' => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm', 'max:'.Submission::VIDEO_MAX_KB],
            'video_url' => ['nullable', 'url:http,https', 'max:255'],
            'website' => ['prohibited'],
        ]);

        $images = collect($request->file('photos', []))->map(fn ($file) => $file->store('submissions', 'public'))->all();
        $video = $request->file('video')?->store('submissions/videos', 'public');
        unset($data['photos'], $data['video']);

        $submission = Submission::create($data + ['images' => $images, 'video_path' => $video]);
        LeadNotifier::submission($submission);

        return redirect()->route('submit')->with('sent', site('submit_success', ['name' => strtok($data['name'], ' ')]));
    }

    public function sitemap()
    {
        $urls = collect([route('home'), route('properties.index'), route('about'), route('contact'), route('blog'), route('submit')])
            ->map(fn ($url) => ['loc' => $url, 'lastmod' => null])
            ->merge(Category::all()->map(fn ($c) => ['loc' => route('properties.index', ['category' => $c->slug]), 'lastmod' => null]))
            ->merge(Property::published()->get(['slug', 'updated_at'])->map(fn ($p) => ['loc' => route('properties.show', $p), 'lastmod' => $p->updated_at]))
            ->merge(Post::live()->get(['slug', 'updated_at'])->map(fn ($p) => ['loc' => route('blog.show', $p), 'lastmod' => $p->updated_at]));

        return response()->view('site.sitemap', ['urls' => $urls])->header('Content-Type', 'application/xml');
    }
}
