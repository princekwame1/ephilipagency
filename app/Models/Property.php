<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Property extends Model
{
    public const OFFERS = ['rent' => 'For rent', 'sale' => 'For sale', 'lease' => 'For lease', 'short_stay' => 'Short stay'];

    public const PERIODS = ['night' => 'night', 'day' => 'day', 'month' => 'month', 'year' => 'year'];

    public const CURRENCIES = ['GHS' => 'GH₵', 'USD' => 'US$'];

    /** Largest video tour upload, in kilobytes (keep below PHP's upload_max_filesize in public/.user.ini). */
    public const VIDEO_MAX_KB = 102400;

    public const AVAILABILITY = ['available' => 'Available', 'under_offer' => 'Under offer', 'sold' => 'Sold', 'rented' => 'Rented'];

    public const SORTS = ['newest' => 'Newest first', 'price_asc' => 'Price: low to high', 'price_desc' => 'Price: high to low', 'popular' => 'Most viewed'];

    protected $fillable = [
        'title', 'slug', 'reference', 'category_id', 'agent_id', 'offer_type', 'status', 'availability',
        'price', 'currency', 'price_period', 'bedrooms', 'bathrooms', 'area', 'parking', 'furnished',
        'location', 'city', 'address', 'latitude', 'longitude', 'summary', 'description', 'video_url', 'video_path',
        'is_featured', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'latitude' => 'float',
            'longitude' => 'float',
            'furnished' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Property $property) {
            $property->slug ??= static::uniqueSlug($property->title);
            $property->reference ??= 'P-'.strtoupper(Str::random(6));
        });

        // Delete image and video files with the listing (each image removes its own file).
        static::deleting(function (Property $property) {
            $property->images->each->delete();
            if ($property->video_path) {
                Storage::disk('public')->delete($property->video_path);
            }
        });
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'property';
        $slug = $base;
        for ($i = 2; static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists(); $i++) {
            $slug = $base.'-'.$i;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ---------------- Relations ----------------

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort')->orderBy('id');
    }

    public function cover(): HasOne
    {
        return $this->hasOne(PropertyImage::class)->ofMany(['sort' => 'min', 'id' => 'min']);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class)->orderBy('name');
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    // ---------------- Scopes ----------------

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /** Search and filters from the listings page query string. */
    public function scopeFilter(Builder $query, array $f): Builder
    {
        $rate = max(0.01, (float) Setting::get('usd_rate', '15.5'));
        // Prices are compared in GH₵ so US$ listings show up in the right range. Bounds are inlined as
        // floats: bound parameters arrive as text, and SQLite then compares them as strings.
        $priceInGhs = "(CASE WHEN currency = 'USD' THEN price * {$rate} ELSE price END)";

        return $query
            ->when($f['q'] ?? null, function (Builder $q, string $term) {
                $q->where(fn (Builder $w) => $w
                    ->where('title', 'like', "%{$term}%")
                    ->orWhere('location', 'like', "%{$term}%")
                    ->orWhere('city', 'like', "%{$term}%")
                    ->orWhere('reference', $term)
                    ->orWhere('summary', 'like', "%{$term}%"));
            })
            ->when($f['offer'] ?? null, fn (Builder $q, $v) => $q->where('offer_type', $v))
            ->when($f['category'] ?? null, fn (Builder $q, $v) => $q->whereHas('category', fn ($c) => $c->where('slug', $v)))
            ->when($f['location'] ?? null, fn (Builder $q, $v) => $q->where('location', $v))
            ->when($f['beds'] ?? null, fn (Builder $q, $v) => $q->where('bedrooms', '>=', (int) $v))
            ->when($f['baths'] ?? null, fn (Builder $q, $v) => $q->where('bathrooms', '>=', (int) $v))
            ->when($f['min'] ?? null, fn (Builder $q, $v) => $q->whereRaw("{$priceInGhs} >= ".(float) $v))
            ->when($f['max'] ?? null, fn (Builder $q, $v) => $q->whereRaw("{$priceInGhs} <= ".(float) $v))
            ->when(! empty($f['furnished']), fn (Builder $q) => $q->where('furnished', true))
            ->when($f['amenities'] ?? null, function (Builder $q, array $ids) {
                foreach ($ids as $id) {
                    $q->whereHas('amenities', fn ($a) => $a->whereKey((int) $id));
                }
            })
            ->tap(fn (Builder $q) => match ($f['sort'] ?? 'newest') {
                'price_asc' => $q->orderByRaw("{$priceInGhs} asc"),
                'price_desc' => $q->orderByRaw("{$priceInGhs} desc"),
                'popular' => $q->orderByDesc('views'),
                default => $q->orderByDesc('is_featured')->latest('published_at')->latest('id'),
            });
    }

    // ---------------- Display helpers ----------------

    public function offerLabel(): string
    {
        return self::OFFERS[$this->offer_type] ?? ucfirst($this->offer_type);
    }

    public function availabilityLabel(): string
    {
        return self::AVAILABILITY[$this->availability] ?? ucfirst($this->availability);
    }

    public function isAvailable(): bool
    {
        return $this->availability === 'available';
    }

    public function currencySymbol(): string
    {
        return self::CURRENCIES[$this->currency] ?? $this->currency;
    }

    /** "GH₵ 4,500" */
    public function formattedPrice(): string
    {
        if ((float) $this->price <= 0) {
            return 'Price on request';
        }
        $decimals = fmod((float) $this->price, 1.0) > 0 ? 2 : 0;

        return $this->currencySymbol().' '.number_format((float) $this->price, $decimals);
    }

    /** "/ month", or null for a one-off price */
    public function periodLabel(): ?string
    {
        return $this->price_period && (float) $this->price > 0 ? '/ '.(self::PERIODS[$this->price_period] ?? $this->price_period) : null;
    }

    /** Price converted to GH₵ (for the US$ to GH₵ hint and the calculator). */
    public function priceInGhs(): float
    {
        $rate = (float) Setting::get('usd_rate', '15.5');

        return $this->currency === 'USD' ? (float) $this->price * $rate : (float) $this->price;
    }

    /** "3 beds · 2 baths · 140 m²" */
    public function factsLine(): string
    {
        return collect([
            $this->bedrooms ? $this->bedrooms.' '.Str::plural('bed', $this->bedrooms) : 'Studio',
            $this->bathrooms ? $this->bathrooms.' '.Str::plural('bath', $this->bathrooms) : null,
            $this->area ? number_format($this->area).' m²' : null,
        ])->filter()->implode(' · ');
    }

    public function coverUrl(): string
    {
        $image = $this->relationLoaded('images') ? $this->images->first() : $this->cover;

        return $image?->url() ?? asset('images/placeholder.svg');
    }

    public function descriptionHtml(): string
    {
        return Str::markdown((string) $this->description, ['html_input' => 'strip', 'allow_unsafe_links' => false]);
    }

    /** OpenStreetMap embed for the pin, when coordinates are set. */
    public function mapEmbedUrl(): ?string
    {
        if (! $this->latitude || ! $this->longitude) {
            return null;
        }
        $d = 0.008;
        $bbox = implode(',', [$this->longitude - $d, $this->latitude - $d, $this->longitude + $d, $this->latitude + $d]);

        return 'https://www.openstreetmap.org/export/embed.html?bbox='.$bbox.'&layer=mapnik&marker='.$this->latitude.','.$this->longitude;
    }

    /**
     * Neighbourhood-level map for the public page: coordinates rounded to about 1 km, a wide view
     * and no marker, so the exact spot is not revealed before a viewing is booked.
     */
    public function areaMapEmbedUrl(): ?string
    {
        if (! $this->latitude || ! $this->longitude) {
            return null;
        }
        $lat = round($this->latitude, 2);
        $lng = round($this->longitude, 2);
        $d = 0.025;

        return 'https://www.openstreetmap.org/export/embed.html?bbox='.implode(',', [$lng - $d, $lat - $d, $lng + $d, $lat + $d]).'&layer=mapnik';
    }

    public function directionsUrl(): string
    {
        $dest = $this->latitude && $this->longitude
            ? $this->latitude.','.$this->longitude
            : $this->location.', '.$this->city.', Ghana';

        return 'https://www.google.com/maps/dir/?api=1&destination='.rawurlencode($dest);
    }

    /** Uploaded video tour, served from public storage. */
    public function videoFileUrl(): ?string
    {
        return $this->video_path ? asset('storage/'.$this->video_path) : null;
    }

    /** YouTube/Vimeo tour as an embeddable URL. */
    public function videoEmbedUrl(): ?string
    {
        $url = (string) $this->video_url;
        if (preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/)|youtu\.be/)([\w-]{11})~i', $url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/'.$m[1].'?rel=0';
        }
        if (preg_match('~vimeo\.com/(?:video/)?(\d+)~i', $url, $m)) {
            return 'https://player.vimeo.com/video/'.$m[1];
        }

        return null;
    }

    /** Agent for this listing, or the business contact as a fallback. */
    public function contactPhone(): string
    {
        return $this->agent?->phone ?: Setting::get('phone');
    }

    public function contactWhatsapp(): ?string
    {
        return Setting::whatsappUrl(
            'Hello, I am interested in '.$this->title.' ('.$this->reference.'): '.route('properties.show', $this),
            $this->agent?->whatsapp ?: null,
        );
    }
}
