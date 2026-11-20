<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = ['title', 'slug', 'excerpt', 'body', 'cover_path', 'is_published', 'published_at'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'published_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::deleted(fn (Post $post) => $post->cover_path && Storage::disk('public')->delete($post->cover_path));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeLive(Builder $query): Builder
    {
        return $query->where('is_published', true)->where('published_at', '<=', now())->latest('published_at');
    }

    public function coverUrl(): ?string
    {
        return $this->cover_path ? asset('storage/'.$this->cover_path) : null;
    }

    /** Body is Markdown; raw HTML is stripped so it is safe to print. */
    public function html(): string
    {
        return Str::markdown($this->body, ['html_input' => 'strip', 'allow_unsafe_links' => false]);
    }

    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->body)) / 220));
    }
}
