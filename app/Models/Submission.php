<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/** A "List your property" request from an owner, reviewed in the dashboard. */
class Submission extends Model
{
    public const STATUSES = ['pending' => 'Pending review', 'approved' => 'Approved', 'rejected' => 'Rejected'];

    /** Largest video an owner can upload with a submission, in kilobytes. */
    public const VIDEO_MAX_KB = 51200;

    protected $fillable = ['name', 'phone', 'email', 'offer_type', 'category_id', 'location', 'price', 'currency', 'bedrooms', 'bathrooms', 'description', 'images', 'video_url', 'video_path', 'status', 'property_id'];

    protected function casts(): array
    {
        return ['images' => 'array'];
    }

    protected static function booted(): void
    {
        static::deleted(fn (Submission $s) => Storage::disk('public')->delete(array_filter([...($s->images ?? []), $s->video_path])));
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
