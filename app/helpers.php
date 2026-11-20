<?php

use App\Models\Setting;

if (! function_exists('site')) {
    /**
     * Editable site text from Admin → Site content (see App\Support\SiteContent).
     * {business} and {tagline} are filled in; pass extra placeholders like ['name' => 'Ama'].
     */
    function site(string $key, array $replace = []): string
    {
        return Setting::text($key, $replace);
    }
}

if (! function_exists('site_shows')) {
    /** Whether a section switched on/off in Admin → Site content is visible. */
    function site_shows(string $key): bool
    {
        return Setting::shows($key);
    }
}
