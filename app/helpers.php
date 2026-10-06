<?php

use App\Services\TranslationService;

if (! function_exists('trans_db')) {
    /**
     * Translate a key using the database-driven translation system.
     *
     * Fallback chain: requested locale → default language → lang files → key.
     *
     * @param  string  $key      Dotted key, e.g. "shop.add_to_cart"
     * @param  string|null  $locale
     * @param  array  $replace  :placeholder replacements
     */
    function trans_db(string $key, ?string $locale = null, array $replace = []): string
    {
        return app(TranslationService::class)->get($key, $locale, $replace);
    }
}

if (! function_exists('setting')) {
    /**
     * Get / set simple key-value platform settings (stored in `settings` table).
     *
     *   setting('theme');                 // get
     *   setting('theme', 'default');      // get with default
     *   setting(['theme' => 'custom']);   // set
     */
    function setting(string|array $key, mixed $default = null): mixed
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                \App\Models\Setting::updateOrCreate(['key' => $k], ['value' => $v]);
                cache()->forget("setting.{$k}");
            }

            return true;
        }

        return cache()->rememberForever("setting.{$key}", function () use ($key, $default) {
            $record = \App\Models\Setting::where('key', $key)->first();

            return $record ? $record->value : $default;
        });
    }
}

if (! function_exists('active_theme')) {
    /**
     * Get the active theme manifest array.
     */
    function active_theme(): ?array
    {
        return app(\App\Services\ThemeManager::class)->active();
    }
}

if (! function_exists('plugin_enabled')) {
    /**
     * Check whether a plugin is enabled.
     */
    function plugin_enabled(string $name): bool
    {
        return app(\App\Services\PluginManager::class)->isEnabled($name);
    }
}
