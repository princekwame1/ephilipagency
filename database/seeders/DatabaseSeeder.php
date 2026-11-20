<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Amenity;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Post;
use App\Models\Property;
use App\Models\Submission;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('ADMIN_PASSWORD', 'password');
        User::updateOrCreate(['email' => env('ADMIN_EMAIL', 'admin@example.com')], [
            'name' => 'Site Admin',
            'password' => Hash::make($password),
        ]);

        // Illustrations in public/images (hero, placeholder). The logo and favicon are brand files in public/.
        File::ensureDirectoryExists(public_path('images'));
        File::put(public_path('images/hero.svg'), Illustration::hero());
        File::put(public_path('images/placeholder.svg'), Illustration::placeholder());

        $categories = collect([
            'Apartment' => 'Flats and apartments for every budget',
            'Luxury apartment' => 'Serviced, high-spec living',
            'House' => 'Family homes and compound houses',
            'Townhouse' => 'Terraced homes in gated estates',
            'Guest house' => 'Rooms and suites for short stays',
            'Airbnb' => 'Furnished stays by the night',
            'Hostel' => 'Student and young professional rooms',
            'Office space' => 'Offices and co-working floors',
            'Commercial property' => 'Shops, warehouses and mixed use',
            'Land' => 'Plots with documented title',
        ])->map(fn ($desc, $name) => Category::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'description' => $desc, 'sort' => 0]));
        $categories->values()->each(fn ($c, $i) => $c->update(['sort' => $i]));

        $amenities = collect(['Air conditioning', 'Security', 'CCTV', 'Parking', 'Swimming pool', 'Gym', 'Borehole', 'Backup generator', 'Fitted kitchen', 'Wi-Fi', 'Balcony', 'Garden', 'Boys quarters', 'Gated community', 'Elevator', 'Conference room', 'Study room', 'Laundry', 'Prepaid meter', 'Housekeeping'])
            ->mapWithKeys(fn ($name) => [$name => Amenity::firstOrCreate(['name' => $name])]);

        $agents = collect([
            ['name' => 'Akosua Mensah', 'role' => 'Head of lettings', 'phone' => '020 000 0001', 'whatsapp' => '020 000 0001', 'email' => 'akosua@example.com', 'bio' => 'Akosua has matched hundreds of families with homes across East Legon, Airport and Cantonments.'],
            ['name' => 'Kwame Owusu', 'role' => 'Sales consultant', 'phone' => '020 000 0002', 'whatsapp' => '020 000 0002', 'email' => 'kwame@example.com', 'bio' => 'Kwame guides buyers and investors through title checks, off-plan purchases and handover.'],
            ['name' => 'Efua Asante', 'role' => 'Short stays & hostels', 'phone' => '020 000 0003', 'whatsapp' => '020 000 0003', 'email' => 'efua@example.com', 'bio' => 'Efua looks after our serviced apartments, guest houses and student hostels.'],
        ])->map(fn ($a) => Agent::updateOrCreate(['email' => $a['email']], $a + ['is_active' => true]));

        $listings = [
            ['Avenue Suites 2-bedroom apartment', 'luxury-apartment', 'short_stay', 1500, 'GHS', 'night', 2, 3, 120, 'Adenta', 5.7146, -0.1564, 'tower', 2, true, ['Air conditioning', 'Security', 'CCTV', 'Parking', 'Fitted kitchen', 'Wi-Fi', 'Borehole', 'Boys quarters', 'Housekeeping'], 'A beautifully furnished two-bedroom apartment in a quiet gated building, ideal for short and extended stays.'],
            ['Ariasville 3-bedroom house with BQ', 'house', 'rent', 12000, 'GHS', 'month', 3, 4, 260, 'East Legon', 5.6370, -0.1610, 'villa', 0, true, ['Security', 'Parking', 'Garden', 'Boys quarters', 'Borehole', 'Gated community', 'Backup generator'], 'Spacious family home with a garden, boys quarters and room for three cars in a secure estate.'],
            ['Cisca luxury apartments', 'luxury-apartment', 'rent', 2500, 'USD', 'month', 2, 2, 140, 'Airport Residential', 5.6055, -0.1780, 'tower', 0, true, ['Air conditioning', 'Swimming pool', 'Gym', 'Elevator', 'Security', 'Backup generator', 'Fitted kitchen', 'Balcony'], 'Serviced apartments with a rooftop pool and gym, five minutes from the airport.'],
            ['R&A luxury townhouses, Saka', 'townhouse', 'sale', 285000, 'USD', null, 4, 5, 320, 'Spintex', 5.6355, -0.0950, 'townhouse', 1, true, ['Security', 'Gated community', 'Parking', 'Fitted kitchen', 'Balcony', 'Boys quarters', 'CCTV'], 'Modern four-bedroom townhouses with private parking and rooftop terraces in a gated community.'],
            ['Reigns One apartment', 'apartment', 'rent', 6500, 'GHS', 'month', 1, 1, 70, 'Madina', 5.6720, -0.1660, 'tower', 2, false, ['Air conditioning', 'Security', 'Prepaid meter', 'Parking', 'Wi-Fi'], 'A bright one-bedroom apartment near the Madina market and the Legon road.'],
            ['Reigns studio apartment', 'apartment', 'short_stay', 650, 'GHS', 'night', 0, 1, 38, 'Madina', 5.6730, -0.1655, 'tower', 2, false, ['Air conditioning', 'Wi-Fi', 'Security', 'Housekeeping'], 'Compact, fully furnished studio for solo travellers and couples.'],
            ['The Equator, off-plan', 'luxury-apartment', 'sale', 145000, 'USD', null, 2, 2, 110, 'Cantonments', 5.5800, -0.1690, 'tower', 1, true, ['Swimming pool', 'Gym', 'Elevator', 'Security', 'CCTV', 'Backup generator', 'Balcony'], 'Pre-launch prices on two-bedroom residences with flexible payment plans over 24 months.'],
            ['Three-bedroom self-compound house', 'house', 'rent', 8000, 'GHS', 'month', 3, 3, 210, 'Adenta', 5.7090, -0.1500, 'villa', 0, false, ['Parking', 'Borehole', 'Garden', 'Prepaid meter', 'Security'], 'Self-contained compound house with its own gate, water storage and space for two cars.'],
            ['Labone garden apartment', 'apartment', 'rent', 1800, 'USD', 'month', 2, 2, 115, 'Labone', 5.5650, -0.1730, 'villa', 1, false, ['Air conditioning', 'Garden', 'Security', 'Parking', 'Fitted kitchen', 'Wi-Fi'], 'Ground-floor apartment opening onto a shared tropical garden on a leafy Labone street.'],
            ['Legon View student hostel', 'hostel', 'rent', 9500, 'GHS', 'year', 1, 1, 18, 'Legon', 5.6510, -0.1870, 'hostel', 2, true, ['Wi-Fi', 'Security', 'Study room', 'Laundry', 'Borehole', 'CCTV'], 'Two-in-a-room and single rooms, a short walk from the university main gate. Price per bed per academic year.'],
            ['Osu boutique guest house', 'guest-house', 'short_stay', 900, 'GHS', 'night', 1, 1, 30, 'Osu', 5.5560, -0.1820, 'villa', 2, false, ['Air conditioning', 'Wi-Fi', 'Housekeeping', 'Security'], 'Characterful rooms with breakfast, steps from Oxford Street restaurants.'],
            ['Dzorwulu executive office floor', 'office-space', 'lease', 35, 'USD', 'month', 0, 2, 450, 'Dzorwulu', 5.6110, -0.2020, 'office', 1, false, ['Air conditioning', 'Elevator', 'Backup generator', 'Parking', 'Conference room', 'Security', 'CCTV'], 'Open-plan office floor with meeting rooms on the N1 corridor. Price per m² per month.'],
            ['Spintex road warehouse and showroom', 'commercial-property', 'lease', 60000, 'GHS', 'year', 0, 2, 900, 'Spintex', 5.6400, -0.1050, 'office', 1, false, ['Parking', 'Security', 'Backup generator', 'CCTV'], 'Roadside showroom with a rear warehouse and loading bay.'],
            ['East Legon Airbnb penthouse', 'airbnb', 'short_stay', 220, 'USD', 'night', 3, 3, 190, 'East Legon', 5.6390, -0.1530, 'tower', 0, true, ['Swimming pool', 'Air conditioning', 'Wi-Fi', 'Elevator', 'Balcony', 'Housekeeping', 'Parking'], 'Top-floor penthouse with a wraparound terrace and city views, sleeps six.'],
            ['Tema Community 25 starter home', 'house', 'sale', 950000, 'GHS', null, 2, 2, 140, 'Tema', 5.7120, 0.0130, 'villa', 0, false, ['Parking', 'Gated community', 'Prepaid meter', 'Security'], 'Affordable two-bedroom home in a fast-growing estate with documented title.'],
            ['Oyarifa half-plot with title', 'land', 'sale', 180000, 'GHS', null, 0, 0, 450, 'Oyarifa', 5.7680, -0.1720, 'villa', 0, false, ['Gated community'], 'Dry, level half plot in a gated layout with registered indenture.'],
        ];

        foreach ($listings as $i => [$title, $cat, $offer, $price, $currency, $period, $beds, $baths, $area, $loc, $lat, $lng, $scene, $agentIdx, $featured, $features, $summary]) {
            $property = Property::updateOrCreate(['slug' => Str::slug($title)], [
                'title' => $title,
                'reference' => 'RH-'.str_pad((string) (101 + $i), 4, '0', STR_PAD_LEFT),
                'category_id' => $categories[collect($categories)->keys()->first(fn ($k) => Str::slug($k) === $cat)]->id,
                'agent_id' => $agents[$agentIdx]->id,
                'offer_type' => $offer,
                'status' => 'published',
                'availability' => $i === 8 ? 'under_offer' : 'available',
                'price' => $price,
                'currency' => $currency,
                'price_period' => $period,
                'bedrooms' => $beds,
                'bathrooms' => $baths,
                'area' => $area,
                'parking' => in_array('Parking', $features) ? rand(1, 3) : 0,
                'furnished' => in_array($offer, ['short_stay']) || in_array('Housekeeping', $features) || $cat === 'luxury-apartment',
                'location' => $loc,
                'city' => $loc === 'Tema' ? 'Tema' : 'Accra',
                'latitude' => $lat,
                'longitude' => $lng,
                'summary' => $summary,
                'description' => $this->description($title, $loc, $summary, $features),
                'is_featured' => $featured,
                'views' => rand(40, 900),
                'published_at' => now()->subDays(count($listings) - $i)->subHours(rand(0, 20)),
            ]);
            $property->amenities()->sync($amenities->only($features)->pluck('id'));

            // Demo photos: one exterior plus interiors (skip interiors for land and commercial).
            $property->images->each->delete();
            $art = new Illustration($i + 1);
            $shots = [['exterior', $scene]];
            if (! in_array($cat, ['land', 'commercial-property', 'office-space'])) {
                $shots = array_merge($shots, [['interior', 'living'], ['interior', $beds ? 'bedroom' : 'living'], ['interior', 'kitchen'], ['exterior', $scene]]);
            } elseif ($cat !== 'land') {
                $shots[] = ['exterior', $scene];
            }
            foreach ($shots as $n => [$type, $variant]) {
                $path = "properties/demo-{$property->id}-{$n}.svg";
                Storage::disk('public')->put($path, $art->$type($variant));
                $property->images()->create(['path' => $path, 'sort' => $n]);
            }
        }

        $this->leads();

        Submission::firstOrCreate(['phone' => '024 000 1111'], [
            'name' => 'Yaw Boateng', 'email' => 'yaw@example.com', 'offer_type' => 'rent',
            'category_id' => $categories['Apartment']->id, 'location' => 'Achimota, near the mall', 'price' => 5500, 'currency' => 'GHS',
            'bedrooms' => 2, 'bathrooms' => 2, 'description' => "Newly built 2-bedroom flat on the first floor. Water tank, prepaid meter and parking for one car. Available from next month.",
            'images' => [],
        ]);

        foreach ([
            ['Renting in Accra: what to budget for beyond the rent', 'Advance rent, agent fees, utilities and the costs nobody mentions until move-in day.'],
            ['Buying off-plan safely: five checks before you pay a deposit', 'How to verify the developer, the land title and the payment schedule before you commit.'],
            ['Student hostels near Legon: a parent\'s checklist', 'Security, water, study space and the questions worth asking on a viewing.'],
        ] as $i => [$title, $excerpt]) {
            Post::updateOrCreate(['slug' => Str::slug($title)], [
                'title' => $title,
                'excerpt' => $excerpt,
                'body' => "## Start with the full picture\n\n{$excerpt} We have helped hundreds of clients through this, and the same few points come up every time.\n\n## The checklist\n\n- Ask for every cost in writing before the viewing\n- Visit at different times of day to check traffic, water and noise\n- Confirm who owns the property and that they can let or sell it\n- Never pay a deposit in cash without a receipt\n\n## Talk to us\n\nOur agents are happy to walk you through any of this. **There is no charge for advice**, and we will tell you honestly if a deal does not look right.",
                'is_published' => true,
                'published_at' => now()->subDays(($i + 1) * 9),
            ]);
        }

        foreach ([
            ['Nana Adjei', 'Tenant, East Legon', 'They showed me three houses in one afternoon, all exactly like the photos. I signed within the week and the agent handled everything with the landlord.'],
            ['Sarah Thompson', 'Relocated from London', 'Finding a furnished place from abroad felt impossible until we found this team. Video viewings, clear prices and the keys were waiting when we landed.'],
            ['Kofi Ansah', 'Owner, Spintex', 'My townhouse was let in under three weeks. Professional photos, serious tenants only, and the rent arrives on time.'],
            ['Abena Darko', 'Parent of a Legon student', 'The hostel was safe, clean and exactly as described. Booking the viewing online took two minutes.'],
        ] as $i => [$name, $role, $quote]) {
            Testimonial::updateOrCreate(['name' => $name], ['role' => $role, 'quote' => $quote, 'rating' => 5, 'is_published' => true, 'sort' => $i]);
        }

        $this->command?->info("Admin sign-in: ".env('ADMIN_EMAIL', 'admin@example.com')." / {$password}  (change it under Account)");
    }

    private function description(string $title, string $location, string $summary, array $features): string
    {
        $list = collect($features)->map(fn ($f) => "- **{$f}**")->implode("\n");

        return "## The home\n\n{$title} sits in a calm part of {$location}, close to shops, schools and main roads. Rooms are bright and well ventilated, finishes are modern, and the property has been inspected by our team.\n\n## Highlights\n\n{$list}\n\n## Viewing\n\nBook a viewing online and we will confirm by phone or WhatsApp. We can also arrange a live video walk-through if you are abroad.";
    }

    /** Sample enquiries spread over the last month, so the dashboard has something to show. */
    private function leads(): void
    {
        if (Enquiry::exists()) {
            return;
        }
        $names = ['Ama Owusu', 'John Mensah', 'Grace Adu', 'Michael Tetteh', 'Esi Quaye', 'Daniel Ofori', 'Linda Badu', 'Samuel Nkrumah', 'Joyce Amponsah', 'Peter Addo', 'Mavis Sarpong', 'Felix Asare'];
        $properties = Property::pluck('id');
        foreach (range(1, 34) as $n) {
            $type = ['enquiry', 'viewing', 'viewing', 'contact'][$n % 4];
            $created = now()->subDays(rand(0, 29))->subMinutes(rand(0, 600));
            $name = $names[$n % count($names)];
            Enquiry::create([
                'property_id' => $type === 'contact' ? null : $properties->random(),
                'type' => $type,
                'name' => $name,
                'phone' => '05'.rand(0, 9).' '.rand(100, 999).' '.rand(1000, 9999),
                'email' => Str::slug($name, '.').'@example.com',
                'message' => $type === 'contact' ? 'Looking for a 2-bedroom in East Legon or Airport, budget around GH₵ 10,000 a month.' : 'Is this still available? I would like to know the advance required.',
                'preferred_date' => $type === 'viewing' ? now()->addDays(rand(-5, 10))->toDateString() : null,
                'preferred_time' => $type === 'viewing' ? ['morning', 'afternoon', 'evening'][rand(0, 2)] : null,
                'status' => $created->gt(now()->subDays(3)) ? 'new' : ['contacted', 'scheduled', 'closed'][rand(0, 2)],
                'created_at' => $created,
                'updated_at' => $created,
            ]);
        }
    }
}
