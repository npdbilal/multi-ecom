<?php

namespace Plugins\MultiVendor;

use App\Services\PluginManager;
use Illuminate\Support\ServiceProvider;
use Plugins\MultiVendor\Http\Middleware\VendorMiddleware;
use Plugins\MultiVendor\Models\Commission;
use Plugins\MultiVendor\Models\Vendor;

class MultiVendorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Middleware: only approved vendors pass.
        $this->app['router']->aliasMiddleware('vendor', VendorMiddleware::class);

        // Plugin routes (storefront vendor area + admin vendor management).
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Plugin views under the "multivendor::" namespace.
        $this->loadViewsFrom(__DIR__.'/resources/views', 'multivendor');

        // Plugin migrations (run with php artisan migrate).
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // Split every order item into vendor earning + platform commission.
        PluginManager::addAction('checkout.completed', function ($order) {
            $defaultRate = (float) setting('multivendor.commission_rate', 10);

            foreach ($order->items as $item) {
                if (! $item->vendor_id) {
                    continue;
                }

                $vendor = Vendor::find($item->vendor_id);

                if (! $vendor) {
                    continue;
                }

                $rate = $vendor->commission_rate ?? $defaultRate;
                $commission = round($item->total * $rate / 100, 2);

                Commission::create([
                    'vendor_id' => $vendor->id,
                    'order_id' => $order->id,
                    'order_item_id' => $item->id,
                    'gross' => $item->total,
                    'commission_rate' => $rate,
                    'commission' => $commission,
                    'earning' => round($item->total - $commission, 2),
                ]);
            }
        });
    }
}
