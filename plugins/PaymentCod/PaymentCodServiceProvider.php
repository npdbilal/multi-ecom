<?php

namespace Plugins\PaymentCod;

use App\Services\PluginManager;
use Illuminate\Support\ServiceProvider;

class PaymentCodServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register Cash on Delivery as a checkout payment method.
        PluginManager::addAction('payment.methods', function (&$methods) {
            $methods['cod'] = [
                'label' => trans_db('shop.cash_on_delivery'),
                'description' => 'Pay in cash when your order arrives.',
            ];
        });

        // COD orders stay "pending" until the admin marks them paid.
        PluginManager::addAction('checkout.completed', function ($order) {
            if ($order->payment_method === 'cod') {
                $order->update(['payment_status' => \App\Models\Order::PAYMENT_PENDING]);
            }
        });
    }
}
