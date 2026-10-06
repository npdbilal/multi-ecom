<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolve the request locale:
 *   1. session('locale') — set via the language switcher
 *   2. default language from the database
 *   3. config fallback
 *
 * Also shares $currentLanguage and $availableLanguages with all views.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale');

        try {
            $available = Language::active()->pluck('code')->all();

            if (! $locale || ! in_array($locale, $available, true)) {
                $locale = Language::default()?->code
                    ?? config('app.fallback_locale', 'en');
            }
        } catch (\Throwable) {
            // Database not migrated yet (fresh install) — use config.
            $locale = config('app.locale', 'en');
            $available = [$locale];
        }

        app()->setLocale($locale);

        try {
            $currentLanguage = Language::where('code', $locale)->first();
            $languages = Language::active()->get();
        } catch (\Throwable) {
            $currentLanguage = null;
            $languages = collect();
        }

        view()->share('currentLanguage', $currentLanguage);
        view()->share('availableLanguages', $languages);
        view()->share('isRtl', (bool) $currentLanguage?->is_rtl);

        return $next($request);
    }
}
