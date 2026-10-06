<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

/**
 * WordPress-style plugin system.
 *
 * Each plugin lives in plugins/{Name}/ with:
 *   - plugin.json manifest:
 *       { "name": "MultiVendor", "version": "1.0.0",
 *         "author": "MultiEcom", "description": "...",
 *         "provider": "Plugins\\MultiVendor\\MultiVendorServiceProvider" }
 *   - a ServiceProvider class referenced by the manifest
 *
 * Enabled plugins are stored via the setting() helper ("plugins" array).
 * The manager registers each enabled plugin's provider during boot.
 *
 * Simple hook system (mirrors WP actions):
 *   PluginManager::addAction('checkout.completed', fn ($order) => ...);
 *   PluginManager::doAction('checkout.completed', $order);
 */
class PluginManager
{
    protected string $pluginsPath;

    /** @var array<string, array<int, callable>> */
    protected static array $actions = [];

    public function __construct()
    {
        $this->pluginsPath = base_path('plugins');
    }

    /**
     * Discover all installed plugins from their manifests.
     *
     * @return array<string, array{name: string, version: string, author: string, description: string, provider: string|null, enabled: bool}>
     */
    public function all(): array
    {
        $plugins = [];

        if (! is_dir($this->pluginsPath)) {
            return $plugins;
        }

        foreach (File::directories($this->pluginsPath) as $dir) {
            $manifest = $dir.'/plugin.json';

            if (! File::exists($manifest)) {
                continue;
            }

            $data = json_decode(File::get($manifest), true);

            if (! is_array($data) || empty($data['name'])) {
                continue;
            }

            $name = $data['name'];

            $plugins[$name] = [
                'name' => $name,
                'version' => $data['version'] ?? '1.0.0',
                'author' => $data['author'] ?? 'Unknown',
                'description' => $data['description'] ?? '',
                'provider' => $data['provider'] ?? null,
                'enabled' => $this->isEnabled($name),
            ];
        }

        ksort($plugins);

        return $plugins;
    }

    /**
     * Register service providers of all enabled plugins.
     * Called from AppServiceProvider::register().
     */
    public function registerEnabled(): void
    {
        foreach ($this->all() as $plugin) {
            if (! $plugin['enabled'] || empty($plugin['provider'])) {
                continue;
            }

            $provider = $plugin['provider'];

            if (class_exists($provider) && is_subclass_of($provider, ServiceProvider::class)) {
                app()->register($provider);
            }
        }
    }

    public function enabled(): array
    {
        try {
            $stored = setting('plugins');
        } catch (\Throwable) {
            $stored = null;
        }

        if (is_array($stored)) {
            return $stored;
        }

        return config('multiecom.plugins', []);
    }

    public function isEnabled(string $name): bool
    {
        return in_array($name, $this->enabled(), true);
    }

    public function enable(string $name): bool
    {
        if (! array_key_exists($name, $this->all())) {
            return false;
        }

        $enabled = $this->enabled();

        if (! in_array($name, $enabled, true)) {
            $enabled[] = $name;
            setting(['plugins' => $enabled]);
        }

        return true;
    }

    public function disable(string $name): bool
    {
        $enabled = array_values(array_diff($this->enabled(), [$name]));
        setting(['plugins' => $enabled]);

        return true;
    }

    // ------------------------------------------------------------------
    // Hook system
    // ------------------------------------------------------------------

    public static function addAction(string $hook, callable $callback, int $priority = 10): void
    {
        static::$actions[$hook][$priority][] = $callback;
        ksort(static::$actions[$hook]);
    }

    public static function doAction(string $hook, &...$args): void
    {
        foreach (static::$actions[$hook] ?? [] as $callbacks) {
            foreach ($callbacks as $callback) {
                $callback(...$args);
            }
        }
    }

    public static function hasAction(string $hook): bool
    {
        return ! empty(static::$actions[$hook]);
    }
}
