<?php

namespace Tests\Feature;

use App\Models\Enquiry;
use App\Models\Property;
use App\Models\Setting;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed();
    }

    public function test_public_pages_load(): void
    {
        $property = Property::first();
        foreach (['/', '/properties', '/about', '/contact', '/blog', '/list-your-property', '/sitemap.xml', '/properties/'.$property->slug, '/properties/saved?ids='.$property->id] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_filters_narrow_results(): void
    {
        $this->get('/properties?offer=sale')
            ->assertOk()
            ->assertSee('The Equator, off-plan')
            ->assertDontSee('Reigns studio apartment');

        $this->get('/properties?category=hostel')->assertSee('Legon View student hostel')->assertDontSee('Cisca luxury apartments');
        $this->get('/properties?q=Labone')->assertSee('Labone garden apartment');
        $this->get('/properties?beds=4')->assertSee('luxury townhouses')->assertDontSee('Reigns One apartment');
    }

    public function test_price_filter_converts_usd_listings(): void
    {
        Setting::put(['usd_rate' => '15']);
        // Cisca is US$ 2,500 a month = GH₵ 37,500, so it is inside 30k–40k and outside 0–20k.
        $this->get('/properties?min=30000&max=40000')->assertSee('Cisca luxury apartments');
        $this->get('/properties?max=20000')->assertDontSee('Cisca luxury apartments');
    }

    public function test_drafts_are_hidden_from_visitors(): void
    {
        $property = Property::first();
        $property->update(['status' => 'draft']);

        $this->get('/properties/'.$property->slug)->assertNotFound();
        $this->get('/properties')->assertDontSee($property->title);
    }

    public function test_visitor_can_request_a_viewing(): void
    {
        $property = Property::first();

        $this->post(route('properties.enquire', $property), [
            'type' => 'viewing', 'name' => 'Test Visitor', 'phone' => '0240000000',
            'preferred_date' => now()->addDays(2)->toDateString(), 'preferred_time' => 'morning',
        ])->assertRedirect()->assertSessionHas('sent');

        $this->assertDatabaseHas('enquiries', ['name' => 'Test Visitor', 'type' => 'viewing', 'property_id' => $property->id, 'status' => 'new']);
    }

    public function test_viewing_needs_a_date_and_honeypot_blocks_bots(): void
    {
        $property = Property::first();

        $this->post(route('properties.enquire', $property), ['type' => 'viewing', 'name' => 'A', 'phone' => '1'])
            ->assertSessionHasErrors('preferred_date');
        $this->post(route('properties.enquire', $property), ['type' => 'enquiry', 'name' => 'Bot', 'phone' => '1', 'website' => 'spam'])
            ->assertSessionHasErrors('website');
        $this->assertDatabaseMissing('enquiries', ['name' => 'Bot']);
    }

    public function test_contact_form_creates_a_lead(): void
    {
        $this->post(route('contact.send'), ['name' => 'Kofi', 'phone' => '020', 'message' => 'Need a house'])
            ->assertSessionHas('sent');
        $this->assertSame('contact', Enquiry::latest('id')->first()->type);
    }

    public function test_owner_can_submit_a_property_with_photos(): void
    {
        $this->post(route('submit.store'), [
            'name' => 'Owner', 'phone' => '024', 'offer_type' => 'rent', 'location' => 'Achimota', 'currency' => 'GHS',
            'photos' => [UploadedFile::fake()->image('front.png', 800, 600)],
        ])->assertRedirect(route('submit'));

        $submission = Submission::latest('id')->first();
        $this->assertSame('Achimota', $submission->location);
        Storage::disk('public')->assertExists($submission->images[0]);
    }

    public function test_clients_can_leave_a_review_that_waits_for_approval(): void
    {
        $this->get(route('review'))->assertOk()->assertSee('How did we do?');

        $this->post(route('review.store'), ['name' => 'Kwame Mensah', 'rating' => 4, 'quote' => 'Great help finding a flat in Adenta.', 'phone' => '020 123 4567'])
            ->assertRedirect(route('review'))
            ->assertSessionHas('sent', 'Thanks Kwame! Your review will appear once our team has checked it.');

        $review = \App\Models\Testimonial::latest('id')->first();
        $this->assertFalse($review->is_published);
        $this->assertSame('website', $review->source);
        $this->get('/')->assertDontSee('Great help finding a flat in Adenta.');

        $this->post(route('review.store'), ['name' => 'Bot', 'rating' => 5, 'quote' => 'Spam spam spam spam', 'website' => 'x'])->assertSessionHasErrors('website');
        $this->post(route('review.store'), ['name' => 'No stars', 'quote' => 'Forgot the rating here'])->assertSessionHasErrors('rating');
    }

    public function test_property_location_stays_locked_until_a_viewing_is_booked(): void
    {
        $property = Property::published()->first();
        $property->update(['address' => '14 Secret Close, Plot 7', 'latitude' => 5.7048213, 'longitude' => -0.1679034]);

        $this->get(route('properties.show', $property))->assertOk()
            ->assertSee('Book a viewing to see the location')
            ->assertSee('bbox=-0.195,5.675,-0.145,5.725', false) // area view, rounded to ~1 km
            ->assertDontSee('14 Secret Close')
            ->assertDontSee('5.7048213')
            ->assertDontSee('-0.1679034')
            ->assertDontSee('marker=', false)
            ->assertDontSee('google.com/maps/dir', false);
    }

    public function test_owner_can_submit_a_video_and_video_link(): void
    {
        $this->post(route('submit.store'), [
            'name' => 'Owner', 'phone' => '024', 'offer_type' => 'rent', 'location' => 'Achimota', 'currency' => 'GHS',
            'video' => UploadedFile::fake()->create('walkthrough.mp4', 4096, 'video/mp4'),
            'video_url' => 'https://youtu.be/dQw4w9WgXcQ',
        ])->assertRedirect(route('submit'));

        $submission = Submission::latest('id')->first();
        Storage::disk('public')->assertExists($submission->video_path);
        $this->assertSame('https://youtu.be/dQw4w9WgXcQ', $submission->video_url);

        $submission->delete();
        Storage::disk('public')->assertMissing($submission->video_path);
    }

    public function test_old_site_links_redirect(): void
    {
        $this->get('/props/offer_type/for-sale')->assertRedirect('/properties?offer=sale');
        $this->get('/props/category/hostel')->assertRedirect('/properties?category=hostel');
        $this->get('/login')->assertRedirect('/admin/login');
    }
}
