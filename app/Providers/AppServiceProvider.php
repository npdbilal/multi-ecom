<?php

namespace App\Providers;

use App\Services\PluginManager;
use App\Services\ThemeManager;
use App\Services\TranslationService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TranslationService::class);
        $this->app->singleton(ThemeManager::class);
        $this->app->singleton(PluginManager::class);

        // Register enabled plugins' service providers.
        $this->app->make(PluginManager::class)->registerEnabled();
    }

    public function boot(): void
    {
        // Alias the admin middleware.
        $router = $this->app['router'];
        $router->aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);

        // Boot the active theme (registers its view paths).
        try {
            $this->app->make(ThemeManager::class)->boot();
        } catch (\Throwable) {
            // Themes table/setting may not exist yet on fresh installs.
        }

        // Blade directive: @transdb('shop.key')
        Blade::directive('transdb', function ($expression) {
            return "<?php echo trans_db({$expression}); ?>";
        });

        // Share the translation service with all views.
        view()->share('translator', $this->app->make(TranslationService::class));
    }
}
