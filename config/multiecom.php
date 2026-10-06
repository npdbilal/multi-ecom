<?php

return [

    /*
    |--------------------------------------------------------------------------
    | MultiEcom platform settings
    |--------------------------------------------------------------------------
    |
    | Central configuration for the theme system, plugin system and
    | translation system. Most values can be changed at runtime from the
    | admin panel; the config file holds the sane defaults.
    |
    */

    // Active storefront theme (directory name inside themes/)
    'theme' => env('MULTIECOM_THEME', 'default'),

    // Enabled plugins (directory names inside plugins/)
    // Managed at runtime via PluginManager — this is the default set.
    'plugins' => [
        // 'MultiVendor',
        'PaymentCod',
        'ShippingFlat',
    ],

    /*
    |--------------------------------------------------------------------------
    | Translations
    |--------------------------------------------------------------------------
    |
    | cache_ttl: seconds to cache resolved translation lookups. Set to 0 to
    | disable caching (useful while editing translations in admin).
    |
    */

    'translations' => [
        'cache_ttl' => env('TRANSLATION_CACHE_TTL', 3600),
    ],

];
