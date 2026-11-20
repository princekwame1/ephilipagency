<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enquiry extends Model
{
    public const TYPES = ['enquiry' => 'Enquiry', 'viewing' => 'Viewing request', 'contact' => 'Contact form'];

    public const STATUSES = ['new' => 'New', 'contacted' => 'Contacted', 'scheduled' => 'Viewing booked', 'closed' => 'Closed'];

    public const TIMES = ['morning' => 'Morning (8am to 12pm)', 'afternoon' => 'Afternoon (12pm to 4pm)', 'evening' => 'Evening (4pm to 6pm)'];

    protected $fillable = ['property_id', 'type', 'name', 'email', 'phone', 'message', 'preferred_date', 'preferred_time', 'status', 'notes'];

    protected function casts(): array
    {
        return ['preferred_date' => 'date'];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }
}
