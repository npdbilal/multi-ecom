<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

/**
 * WordPress-style theme system.
 *
 * Each theme lives in themes/{name}/ with a theme.json manifest:
 *
 *   {
 *     "name": "Default",
 *     "slug": "default",
 *     "version": "1.0.0",
 *     "author": "MultiEcom",
 *     "description": "Clean modern storefront"
 *   }
 *
 * The active theme's views/ directory is prepended to Laravel's view
 * finder paths, so theme Blade files override core ones by name.
 * Screenshot: themes/{name}/screenshot.png (optional).
 */
class ThemeManager
{
    protected string $themesPath;

    public function __construct()
    {
        $this->themesPath = base_path('themes');
    }

    /**
     * Boot the active theme: register its view path with top priority.
     */
    public function boot(): void
    {
        $theme = $this->active();

        if (! $theme) {
            return;
        }

        $viewsPath = $this->themesPath.'/'.$theme['slug'].'/views';

        if (is_dir($viewsPath)) {
            View::addLocation($viewsPath);
            // Ensure theme path wins over core paths.
            $finder = View::getFinder();
            $paths = $finder->getPaths();
            $finder->setPaths(array_unique(array_merge([$viewsPath], $paths)));
        }

        View::share('activeTheme', $theme);
    }

    /**
     * Discover all installed themes.
     *
     * @return array<int, array{slug: string, name: string, version: string, author: string, description: string, active: bool}>
     */
    public function all(): array
    {
        $themes = [];

        if (! is_dir($this->themesPath)) {
            return $themes;
        }

        foreach (File::directories($this->themesPath) as $dir) {
            $manifest = $dir.'/theme.json';

            if (! File::exists($manifest)) {
                continue;
            }

            $data = json_decode(File::get($manifest), true);

            if (! is_array($data)) {
                continue;
            }

            $slug = basename($dir);

            $themes[] = [
                'slug' => $slug,
                'name' => $data['name'] ?? $slug,
                'version' => $data['version'] ?? '1.0.0',
                'author' => $data['author'] ?? 'Unknown',
                'description' => $data['description'] ?? '',
                'screenshot' => File::exists($dir.'/screenshot.png') ? "themes/{$slug}/screenshot.png" : null,
                'active' => $slug === $this->activeSlug(),
            ];
        }

        return $themes;
    }

    public function active(): ?array
    {
        $slug = $this->activeSlug();

        foreach ($this->all() as $theme) {
            if ($theme['slug'] === $slug) {
                return $theme;
            }
        }

        // Fall back to the first available theme.
        return $this->all()[0] ?? null;
    }

    public function activeSlug(): string
    {
        try {
            return setting('theme', config('multiecom.theme', 'default'));
        } catch (\Throwable) {
            return config('multiecom.theme', 'default');
        }
    }

    public function activate(string $slug): bool
    {
        $slugs = array_column($this->all(), 'slug');

        if (! in_array($slug, $slugs, true)) {
            return false;
        }

        setting(['theme' => $slug]);

        return true;
    }
}
