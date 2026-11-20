<?php

namespace App\Models;

use App\Support\SiteContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/** Key/value site settings (brand, contact details, hero text, links), cached as one array. */
class Setting extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'key';

    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    public const DEFAULTS = [
        'business_name' => 'E-Philip Premium Agency',
        'tagline' => 'Real estate & accommodation in Ghana',
        'hero_title' => 'Find a place that feels like home.',
        'hero_text' => 'Apartments, houses, hostels, short stays and commercial spaces across Accra, verified and ready to view.',
        'about_title' => 'A calmer way to rent, buy and stay.',
        'about' => "We help people find homes, short stays and workspaces across Ghana. Every listing is visited and checked by our team before it goes live, prices are shown up front, and one agent walks you through the whole process, from the first viewing to the keys.\n\nWhether you are relocating, investing, looking for a student hostel or booking a few nights in a serviced apartment, we keep things simple, honest and quick.",
        'phone' => '020 000 0000',
        'whatsapp' => '020 000 0000',
        'email' => 'hello@example.com',
        'address' => 'Adenta, Accra',
        'hours' => 'Mon to Sat, 8am to 6pm',
        'facebook' => '',
        'instagram' => '',
        'tiktok' => '',
        'linkedin' => '',
        'logo_path' => '',
        'hero_image_path' => '',
        'usd_rate' => '15.5', // GH₵ per US$, used for the price filter and the converter
        'stat_clients' => '1,200+',
        'stat_years' => '8',
        'notify_email' => '', // where new enquiries are emailed (blank = no email)
    ];

    /** Settings for this request (saved values over defaults). Kept in memory: views read it many times. */
    private static ?array $memo = null;

    private static ?int $memoApp = null; // tied to the app instance so tests and queue workers never see stale values

    public static function values(): array
    {
        if (static::$memoApp !== spl_object_id(app())) {
            static::$memo = null;
            static::$memoApp = spl_object_id(app());
        }

        return static::$memo ??= array_merge(
            self::DEFAULTS,
            SiteContent::defaults(),
            Cache::rememberForever('site.settings.saved', fn () => static::query()->pluck('value', 'key')->map(fn ($v) => (string) $v)->all()),
        );
    }

    public static function flushMemo(): void
    {
        static::$memo = null;
    }

    /** Editable site text with {business}, {tagline} and any extra {placeholders} filled in. */
    public static function text(string $key, array $replace = []): string
    {
        $v = static::values();
        $replace += ['business' => $v['business_name'] ?? '', 'tagline' => $v['tagline'] ?? ''];

        return strtr((string) ($v[$key] ?? ''), collect($replace)->mapWithKeys(fn ($r, $k) => ['{'.$k.'}' => (string) $r])->all());
    }

    /** Section switches from Site content (on unless turned off). */
    public static function shows(string $key): bool
    {
        return (static::values()[$key] ?? '1') !== '0';
    }

    /** Uploaded image for a Site content image field, or its built-in default. */
    public static function imageUrl(string $key): string
    {
        $path = static::get($key);

        return $path ? asset('storage/'.$path) : asset(SiteContent::fields()[$key]['default'] ?? 'images/placeholder.svg');
    }

    public static function get(string $key, ?string $default = null): string
    {
        return (string) (static::values()[$key] ?? $default ?? '');
    }

    public static function put(array $values): void
    {
        foreach ($values as $key => $value) {
            static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget('site.settings.saved');
        static::flushMemo();
    }

    /** Dialable number: Ghana-style 0XX XXX XXXX becomes +233XXXXXXXXX. */
    public static function tel(?string $number): string
    {
        $tel = preg_replace('/[^\d+]/', '', (string) $number);

        return preg_match('/^0\d{9}$/', $tel) ? '+233'.substr($tel, 1) : $tel;
    }

    /** wa.me link for a WhatsApp number (defaults to the business number). */
    public static function whatsappUrl(?string $text = null, ?string $number = null): ?string
    {
        $digits = preg_replace('/\D/', '', $number ?? static::get('whatsapp'));
        if ($digits === '') {
            return null;
        }
        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            $digits = '233'.substr($digits, 1);
        }

        return 'https://wa.me/'.$digits.($text ? '?text='.rawurlencode($text) : '');
    }

    public static function logoUrl(): ?string
    {
        $path = static::get('logo_path');

        return $path ? asset('storage/'.$path) : null;
    }

    public static function heroImageUrl(): string
    {
        $path = static::get('hero_image_path');

        return $path ? asset('storage/'.$path) : asset('images/hero-villa.webp');
    }
}
