<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

/**
 * WordPress-style theme ZIP installer.
 *
 * ZIP must contain theme.json at root (or one wrapper folder deep):
 *   { "name": "My Theme", "slug": "my-theme", "version": "1.0.0",
 *     "author": "Author", "description": "...", "screenshot": "screenshot.png" }
 *
 * Installs to themes/{slug}/
 */
class ThemeInstaller extends ZipInstaller
{
    protected function manifestFile(): string
    {
        return 'theme.json';
    }

    protected function installPath(): string
    {
        return base_path('themes');
    }

    protected function validateManifest(array $manifest, string $tmpDir, string $packageRoot): void
    {
        if (empty($manifest['version'])) {
            throw new \InvalidArgumentException('Invalid theme.json: missing required "version".');
        }
    }

    /**
     * Install a theme from an uploaded ZIP.
     *
     * @return string installed slug
     * @throws \InvalidArgumentException
     */
    public function install(UploadedFile $file): string
    {
        [$manifest, $packageRoot, $tmpDir] = $this->validateAndExtract($file);

        $slug = $this->sanitizeSlug($manifest['slug'] ?? $manifest['name']);

        return $this->moveIntoPlace($packageRoot, $tmpDir, $slug);
    }

    /**
     * Delete an installed theme. Refuses to delete the active theme
     * or the bundled "default" theme.
     *
     * @throws \InvalidArgumentException
     */
    public function delete(string $slug, ThemeManager $themes): bool
    {
        $slug = $this->sanitizeSlug($slug);

        if ($slug === 'default') {
            throw new \InvalidArgumentException('The bundled "default" theme cannot be deleted.');
        }

        if ($themes->active() === $slug) {
            throw new \InvalidArgumentException('Cannot delete the active theme. Activate another theme first.');
        }

        $dir = $this->installPath().'/'.$slug;

        if (! is_dir($dir)) {
            throw new \InvalidArgumentException('Theme not found.');
        }

        return \Illuminate\Support\Facades\File::deleteDirectory($dir);
    }
}
