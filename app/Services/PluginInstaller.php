<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

/**
 * WordPress-style plugin ZIP installer.
 *
 * ZIP must contain plugin.json at root (or one wrapper folder deep):
 *   { "name": "MyPlugin", "slug": "my-plugin", "version": "1.0.0",
 *     "author": "Author", "description": "...",
 *     "provider": "Plugins\\MyPlugin\\MyPluginServiceProvider" }
 *
 * Installs to plugins/{Name}/  (directory uses the "name" as before,
 * matching PluginManager's discovery by plugin.json "name").
 *
 * On enable: runs the plugin's database/migrations (if any).
 * On disable: provider is simply not registered; data is kept.
 */
class PluginInstaller extends ZipInstaller
{
    /** Core-bundled plugins that cannot be deleted. */
    protected array $protected = ['MultiVendor', 'PaymentCod', 'ShippingFlat'];

    protected function manifestFile(): string
    {
        return 'plugin.json';
    }

    protected function installPath(): string
    {
        return base_path('plugins');
    }

    protected function validateManifest(array $manifest, string $tmpDir, string $packageRoot): void
    {
        if (empty($manifest['version'])) {
            throw new \InvalidArgumentException('Invalid plugin.json: missing required "version".');
        }

        if (empty($manifest['provider'])) {
            throw new \InvalidArgumentException('Invalid plugin.json: missing required "provider".');
        }

        // Provider must look like a namespaced class: Plugins\Name\XxxServiceProvider
        if (! preg_match('#^Plugins\\\\\\\\[A-Za-z0-9_]+\\\\\\\\[A-Za-z0-9_]+$#', $manifest['provider'])
            && ! preg_match('#^Plugins\\\\[A-Za-z0-9_]+\\\\[A-Za-z0-9_]+$#', $manifest['provider'])) {
            throw new \InvalidArgumentException('Invalid plugin.json: "provider" must be a Plugins\\{Name}\\{Class} class.');
        }

        // The provider PHP file should exist inside the package.
        // Derive expected relative path: Plugins\Name\Class → src or root Class.php
        $parts = explode('\\', $manifest['provider']);
        $classFile = end($parts).'.php';

        $found = File::exists($packageRoot.'/'.$classFile)
            || File::exists($packageRoot.'/src/'.$classFile);

        // Also accept any *ServiceProvider.php anywhere one level deep
        if (! $found) {
            $candidates = array_merge(
                File::glob($packageRoot.'/*ServiceProvider.php') ?: [],
                File::glob($packageRoot.'/src/*ServiceProvider.php') ?: []
            );
            $found = ! empty($candidates);
        }

        if (! $found) {
            throw new \InvalidArgumentException(
                "Invalid plugin: service provider file for \"{$manifest['provider']}\" not found in ZIP."
            );
        }
    }

    /**
     * Install a plugin from an uploaded ZIP.
     *
     * @return string installed name (directory name)
     * @throws \InvalidArgumentException
     */
    public function install(UploadedFile $file): string
    {
        [$manifest, $packageRoot, $tmpDir] = $this->validateAndExtract($file);

        // Directory uses the plugin "name" (matches PluginManager discovery).
        // Sanitize but preserve case for PSR-4-ish names: allow alnum only.
        $name = preg_replace('/[^A-Za-z0-9]/', '', $manifest['name']);

        if ($name === '') {
            File::deleteDirectory($tmpDir);
            throw new \InvalidArgumentException('Invalid plugin name in manifest.');
        }

        return $this->moveIntoPlace($packageRoot, $tmpDir, $name);
    }

    /**
     * Run the plugin's migrations (called on enable).
     */
    public function migrate(string $name): void
    {
        $migrationsPath = $this->installPath().'/'.$name.'/database/migrations';

        if (is_dir($migrationsPath)) {
            Artisan::call('migrate', [
                '--path' => 'plugins/'.$name.'/database/migrations',
                '--force' => true,
            ]);
        }
    }

    /**
     * Delete an installed plugin. Refuses bundled plugins and
     * enabled plugins (disable first).
     *
     * @throws \InvalidArgumentException
     */
    public function delete(string $name, PluginManager $plugins): bool
    {
        $name = preg_replace('/[^A-Za-z0-9]/', '', $name);

        if (in_array($name, $this->protected, true)) {
            throw new \InvalidArgumentException("The bundled \"{$name}\" plugin cannot be deleted.");
        }

        if ($plugins->isEnabled($name)) {
            throw new \InvalidArgumentException('Disable the plugin before deleting it.');
        }

        $dir = $this->installPath().'/'.$name;

        if (! is_dir($dir)) {
            throw new \InvalidArgumentException('Plugin not found.');
        }

        return File::deleteDirectory($dir);
    }
}
