<?php

namespace Plugins\ShippingFlat;

use App\Services\PluginManager;
use Illuminate\Support\ServiceProvider;

class ShippingFlatServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register a flat-rate shipping method. Rate comes from settings
        // (Admin → Plugins → ShippingFlat is read here; default $5.00).
        PluginManager::addAction('shipping.methods', function ($subtotal, &$methods) {
            $rate = (float) setting('shipping.flat_rate', 5.00);
            $freeOver = (float) setting('shipping.free_over', 0);

            $cost = ($freeOver > 0 && $subtotal >= $freeOver) ? 0 : $rate;

            $methods['flat'] = [
                'label' => trans_db('shop.flat_rate_shipping') !== 'shop.flat_rate_shipping'
                    ? trans_db('shop.flat_rate_shipping')
                    : 'Flat Rate Shipping',
                'cost' => $cost,
            ];
        });
    }
}
