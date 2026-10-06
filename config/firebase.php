<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Firebase Client Configuration
    |--------------------------------------------------------------------------
    |
    | These values come from the Firebase Console → Project Settings → Your
    | apps → Web app. They are exposed to the Blade login views so the
    | Firebase JS SDK can initialize in the browser.
    |
    */

    'api_key' => env('FIREBASE_API_KEY'),
    'auth_domain' => env('FIREBASE_AUTH_DOMAIN'),
    'project_id' => env('FIREBASE_PROJECT_ID'),
    'storage_bucket' => env('FIREBASE_STORAGE_BUCKET'),
    'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID'),
    'app_id' => env('FIREBASE_APP_ID'),
    'measurement_id' => env('FIREBASE_MEASUREMENT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Firebase Admin SDK (server-side token verification)
    |--------------------------------------------------------------------------
    |
    | The kreait/laravel-firebase package reads its own FIREBASE_* variables
    | (see vendor/kreait/laravel-firebase/config/firebase.php). For local
    | development you can point FIREBASE_CREDENTIALS to a service-account
    | JSON file downloaded from Firebase Console → Project Settings →
    | Service accounts.
    |
    */

    'credentials' => env('FIREBASE_CREDENTIALS'),

];
