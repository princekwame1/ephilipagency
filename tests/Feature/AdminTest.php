<?php

namespace Tests\Feature;

use App\Models\Agent;
use App\Models\Amenity;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Post;
use App\Models\Property;
use App\Models\Setting;
use App\Models\Submission;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed();
        $this->admin = User::first();
    }

    public function test_guests_are_sent_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_can_sign_in(): void
    {
        $this->post('/admin/login', ['email' => $this->admin->email, 'password' => 'password'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_every_admin_page_loads(): void
    {
        $this->actingAs($this->admin);
        $property = Property::first();
        $urls = [
            '/admin', '/admin/properties', '/admin/properties/create', "/admin/properties/{$property->slug}/edit",
            '/admin/enquiries', '/admin/enquiries/'.Enquiry::first()->id, '/admin/enquiries/export',
            '/admin/submissions', '/admin/submissions/'.Submission::first()->id,
            '/admin/categories', '/admin/categories/create', '/admin/categories/'.Category::first()->slug.'/edit',
            '/admin/amenities', '/admin/agents', '/admin/agents/create', '/admin/agents/'.Agent::first()->id.'/edit',
            '/admin/posts', '/admin/posts/create', '/admin/posts/'.Post::first()->slug.'/edit',
            '/admin/testimonials', '/admin/testimonials/create', '/admin/testimonials/'.Testimonial::first()->id.'/edit',
            '/admin/settings', '/admin/account',
            // Staff can preview drafts
            '/properties/'.$property->slug,
        ];
        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_create_listing_with_photos_and_amenities(): void
    {
        $this->actingAs($this->admin);
        $amenities = Amenity::take(2)->pluck('id')->all();

        $response = $this->post('/admin/properties', [
            'title' => 'Sea view villa', 'offer_type' => 'sale', 'status' => 'published', 'availability' => 'available',
            'price' => 500000, 'currency' => 'USD', 'price_period' => '', 'bedrooms' => 5, 'bathrooms' => 6,
            'location' => 'Labadi', 'city' => 'Accra', 'category_id' => Category::first()->id,
            'amenities' => $amenities, 'is_featured' => 1,
            'photos' => [UploadedFile::fake()->image('a.png'), UploadedFile::fake()->image('b.png')],
        ]);

        $property = Property::where('title', 'Sea view villa')->firstOrFail();
        $response->assertRedirect("/admin/properties/{$property->slug}/edit");
        $this->assertSame('sea-view-villa', $property->slug);
        $this->assertNotNull($property->published_at);
        $this->assertTrue($property->is_featured);
        $this->assertCount(2, $property->images);
        $this->assertEqualsCanonicalizing($amenities, $property->amenities->pluck('id')->all());
        $this->get('/properties/sea-view-villa')->assertOk()->assertSee('US$ 500,000');
    }

    public function test_video_tour_upload_replace_and_remove(): void
    {
        $this->actingAs($this->admin);
        $property = Property::first();
        $fields = $property->only(['title', 'offer_type', 'status', 'availability', 'price', 'currency', 'price_period', 'bedrooms', 'bathrooms', 'location', 'city']);

        $this->put(route('admin.properties.update', $property), $fields + [
            'video' => UploadedFile::fake()->create('tour.mp4', 2048, 'video/mp4'),
            'video_url' => 'https://www.tiktok.com/@agent/video/123',
        ])->assertSessionHasNoErrors();
        $first = $property->fresh()->video_path;
        Storage::disk('public')->assertExists($first);
        $this->get(route('properties.show', $property))->assertOk()
            ->assertSee('<video src="'.asset('storage/'.$first).'"', false)
            ->assertSee('Watch the video tour');

        $this->put(route('admin.properties.update', $property), $fields + ['video' => UploadedFile::fake()->create('new.mov', 1024, 'video/quicktime')]);
        $second = $property->fresh()->video_path;
        $this->assertNotSame($first, $second);
        Storage::disk('public')->assertMissing($first);

        $this->put(route('admin.properties.update', $property), $fields + ['remove_video' => 1]);
        $this->assertNull($property->fresh()->video_path);
        Storage::disk('public')->assertMissing($second);

        $this->put(route('admin.properties.update', $property), $fields + ['video' => UploadedFile::fake()->create('virus.exe', 10, 'application/x-msdownload')])
            ->assertSessionHasErrors('video');
        $this->put(route('admin.properties.update', $property), $fields + ['video_url' => 'javascript:alert(1)'])
            ->assertSessionHasErrors('video_url');
    }

    public function test_edit_form_preselects_saved_values_without_javascript(): void
    {
        $this->actingAs($this->admin);
        $property = Property::where('offer_type', 'short_stay')->first();

        $this->get("/admin/properties/{$property->slug}/edit")
            ->assertSee('<option value="short_stay" selected>', false);
    }

    public function test_photo_cover_and_delete(): void
    {
        $this->actingAs($this->admin);
        $property = Property::with('images')->first();
        [$first, $second] = $property->images->take(2)->all();

        $this->post(route('admin.properties.images.cover', [$property, $second]))->assertRedirect();
        $this->assertSame($second->id, $property->fresh()->images->first()->id);

        $this->delete(route('admin.properties.images.destroy', [$property, $first]))->assertRedirect();
        $this->assertDatabaseMissing('property_images', ['id' => $first->id]);
        Storage::disk('public')->assertMissing($first->path);
    }

    public function test_quick_actions_and_duplicate(): void
    {
        $this->actingAs($this->admin);
        $property = Property::first();

        $this->patch(route('admin.properties.quick', $property), ['availability' => 'rented'])->assertRedirect();
        $this->assertSame('rented', $property->fresh()->availability);

        $this->post(route('admin.properties.duplicate', $property))->assertRedirect();
        $copy = Property::latest('id')->first();
        $this->assertSame('draft', $copy->status);
        $this->assertNotSame($property->slug, $copy->slug);
        $this->assertNotSame($property->reference, $copy->reference);
    }

    public function test_deleting_a_listing_removes_its_photos(): void
    {
        $this->actingAs($this->admin);
        $property = Property::with('images')->first();
        $paths = $property->images->pluck('path');

        $this->delete(route('admin.properties.destroy', $property))->assertRedirect('/admin/properties');
        $paths->each(fn ($p) => Storage::disk('public')->assertMissing($p));
    }

    public function test_submission_converts_to_draft_listing(): void
    {
        $this->actingAs($this->admin);
        $submission = Submission::first();

        $this->post(route('admin.submissions.convert', $submission))->assertRedirect();

        $submission->refresh();
        $this->assertSame('approved', $submission->status);
        $this->assertSame('draft', $submission->property->status);
        $this->assertSame('Achimota, near the mall', $submission->property->location);
    }

    public function test_submission_video_is_copied_to_the_draft_listing(): void
    {
        $this->actingAs($this->admin);
        Storage::disk('public')->put('submissions/videos/tour.mp4', 'video');
        $submission = Submission::first();
        $submission->update(['video_path' => 'submissions/videos/tour.mp4', 'video_url' => 'https://youtu.be/dQw4w9WgXcQ']);

        $this->get(route('admin.submissions.show', $submission))->assertOk()->assertSee('https://youtu.be/dQw4w9WgXcQ');
        $this->post(route('admin.submissions.convert', $submission))->assertRedirect();

        $property = $submission->fresh()->property;
        $this->assertSame('properties/videos/tour.mp4', $property->video_path);
        $this->assertSame('https://youtu.be/dQw4w9WgXcQ', $property->video_url);
        Storage::disk('public')->assertExists('properties/videos/tour.mp4');
    }

    public function test_lead_status_update(): void
    {
        $this->actingAs($this->admin);
        $lead = Enquiry::first();

        $this->put(route('admin.enquiries.update', $lead), ['status' => 'closed', 'notes' => 'Signed lease'])->assertRedirect();
        $this->assertSame('Signed lease', $lead->fresh()->notes);
    }

    public function test_settings_update_shows_on_site(): void
    {
        $this->actingAs($this->admin);

        $this->put('/admin/settings', [
            'business_name' => 'Golden Keys', 'usd_rate' => '16',
        ])->assertRedirect();

        $this->assertSame('Golden Keys', Setting::get('business_name'));
        $this->get('/')->assertSee('Golden Keys')->assertSee('Why Golden Keys'); // {business} placeholder
    }

    public function test_every_site_content_tab_loads_and_saves(): void
    {
        $this->actingAs($this->admin);

        foreach (array_keys(\App\Support\SiteContent::pages()) as $page) {
            $this->get(route('admin.content', $page))->assertOk();
        }
        $this->get(route('admin.content', 'nope'))->assertNotFound();

        $home = collect(\App\Support\SiteContent::pageFields('home'))->map(fn ($f, $key) => Setting::get($key))->all();
        $this->put(route('admin.content.update', 'home'), [
            'hero_title' => 'Your royal welcome home',
            'home_why_title' => 'Why people choose us',
            'home_featured_show' => '0',
            'hero_image_path' => UploadedFile::fake()->image('hero.png', 1600, 900),
        ] + $home)->assertRedirect()->assertSessionHasNoErrors();

        $this->get('/')->assertOk()
            ->assertSee('Your royal welcome home')
            ->assertSee('Why people choose us')
            ->assertDontSee(Setting::get('home_featured_title'));
        Storage::disk('public')->assertExists(Setting::get('hero_image_path'));

        $this->put(route('admin.content.update', 'layout'), ['nav_contact' => 'Talk to us', 'nav_about_show' => '0'] + collect(\App\Support\SiteContent::pageFields('layout'))->map(fn ($f, $key) => Setting::get($key))->except(['nav_contact', 'nav_about_show'])->all());
        $this->get('/contact')->assertSee('Talk to us')->assertDontSee('>About</a>', false);

        // Reset puts the original wording back and removes the uploaded photo.
        $photo = Setting::get('hero_image_path');
        $this->post(route('admin.content.reset', 'home'))->assertRedirect();
        $this->get('/')->assertSee('Find a place that feels like home.')->assertSee('Featured properties');
        Storage::disk('public')->assertMissing($photo);
    }

    public function test_website_reviews_wait_for_approval(): void
    {
        $this->actingAs($this->admin);
        $review = Testimonial::create(['name' => 'Akosua B.', 'quote' => 'Smooth viewing and honest agent.', 'rating' => 5, 'is_published' => false, 'source' => 'website', 'phone' => '024 111 2222']);

        $this->get(route('admin.testimonials.index'))->assertSee('Waiting for approval')->assertSee(route('review'));
        $this->get('/')->assertDontSee('Smooth viewing and honest agent.');

        $this->post(route('admin.testimonials.publish', $review))->assertRedirect();
        $this->assertTrue($review->fresh()->is_published);
        $this->get('/')->assertSee('Smooth viewing and honest agent.')->assertDontSee('024 111 2222');
    }

    public function test_content_crud(): void
    {
        $this->actingAs($this->admin);

        $this->post('/admin/categories', ['name' => 'Penthouse'])->assertRedirect('/admin/categories');
        $this->post('/admin/amenities', ['name' => 'Rooftop'])->assertRedirect();
        $this->post('/admin/agents', ['name' => 'New Agent', 'is_active' => 1])->assertRedirect('/admin/agents');
        $this->post('/admin/posts', ['title' => 'Hello world', 'body' => 'Body text', 'is_published' => 1])->assertRedirect('/admin/posts');
        $this->post('/admin/testimonials', ['name' => 'Fan', 'quote' => 'Great', 'rating' => 5, 'is_published' => 1])->assertRedirect('/admin/testimonials');

        $this->assertDatabaseHas('categories', ['slug' => 'penthouse']);
        $this->assertDatabaseHas('amenities', ['name' => 'Rooftop']);
        $this->assertDatabaseHas('agents', ['name' => 'New Agent']);
        $this->get('/blog/hello-world')->assertOk();
    }
}
