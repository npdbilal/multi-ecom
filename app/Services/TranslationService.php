<?php

namespace App\Services;

use App\Models\Language;
use App\Models\Translation;
use Illuminate\Support\Facades\Cache;

/**
 * Database-driven translation service.
 *
 * Lookup order (first hit wins):
 *   1. translations table for the requested locale
 *   2. translations table for the default language
 *   3. Laravel lang files (__)
 *   4. the raw key itself
 */
class TranslationService
{
    protected ?string $defaultCode = null;

    /**
     * Get a translated string.
     *
     * @param  string  $key     Dotted key, e.g. "shop.add_to_cart"
     * @param  string|null  $locale  Defaults to the current app locale
     * @param  array  $replace  :placeholder replacements
     */
    public function get(string $key, ?string $locale = null, array $replace = []): string
    {
        $locale = $locale ?: app()->getLocale();

        [$group, $item] = $this->splitKey($key);

        $value = $this->lookup($group, $item, $locale);

        if ($value === null && $locale !== $this->defaultCode()) {
            $value = $this->lookup($group, $item, $this->defaultCode());
        }

        if ($value === null) {
            // Fall back to classic Laravel lang files, then the raw key.
            $value = __($key, $replace) === $key ? $key : __($key, $replace);

            return $this->replacePlaceholders($value, $replace);
        }

        return $this->replacePlaceholders($value, $replace);
    }

    /**
     * Check whether a translation exists in the database.
     */
    public function has(string $key, ?string $locale = null): bool
    {
        $locale = $locale ?: app()->getLocale();
        [$group, $item] = $this->splitKey($key);

        return $this->lookup($group, $item, $locale) !== null;
    }

    /**
     * Store or update a translation, then bust the cache.
     */
    public function set(string $key, string $locale, string $value): Translation
    {
        [$group, $item] = $this->splitKey($key);

        $translation = Translation::updateOrCreate(
            ['language_code' => $locale, 'group' => $group, 'key' => $item],
            ['value' => $value]
        );

        $this->flushCache($group, $item, $locale);

        return $translation;
    }

    public function flushCache(?string $group = null, ?string $item = null, ?string $locale = null): void
    {
        if ($group && $item && $locale) {
            Cache::forget($this->cacheKey($group, $item, $locale));

            return;
        }

        // Flush all translation cache entries.
        Cache::flush();
    }

    /**
     * All translations for a locale, grouped: ['shop' => ['key' => 'value']].
     */
    public function allFor(string $locale): array
    {
        $grouped = [];

        foreach (Translation::where('language_code', $locale)->get() as $t) {
            $grouped[$t->group][$t->key] = $t->value;
        }

        return $grouped;
    }

    // -----------------------------------------------------------------

    protected function lookup(string $group, string $item, string $locale): ?string
    {
        $ttl = (int) config('multiecom.translations.cache_ttl', 3600);

        $resolver = fn () => Translation::where('language_code', $locale)
            ->where('group', $group)
            ->where('key', $item)
            ->value('value');

        if ($ttl <= 0) {
            return $resolver();
        }

        return Cache::remember($this->cacheKey($group, $item, $locale), $ttl, $resolver);
    }

    protected function cacheKey(string $group, string $item, string $locale): string
    {
        return "trans.{$locale}.{$group}.{$item}";
    }

    protected function splitKey(string $key): array
    {
        if (! str_contains($key, '.')) {
            return ['general', $key];
        }

        $pos = strpos($key, '.');

        return [substr($key, 0, $pos), substr($key, $pos + 1)];
    }

    protected function defaultCode(): string
    {
        if ($this->defaultCode === null) {
            try {
                $this->defaultCode = Language::default()?->code
                    ?? config('app.fallback_locale', 'en');
            } catch (\Throwable) {
                // Database may not be migrated yet (e.g. during install).
                $this->defaultCode = config('app.fallback_locale', 'en');
            }
        }

        return $this->defaultCode;
    }

    protected function replacePlaceholders(string $value, array $replace): string
    {
        foreach ($replace as $search => $replacement) {
            $value = str_replace(':'.$search, (string) $replacement, $value);
        }

        return $value;
    }
}
