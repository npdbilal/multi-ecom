<?php

namespace App\Services;

use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

/**
 * Server-side Firebase Authentication.
 *
 * The browser signs the user in with the Firebase JS SDK (Phone OTP,
 * Email+Password, or Google). It then POSTs the Firebase ID token to
 * FirebaseAuthController@verify, which uses this service to cryptographically
 * verify the token against the Firebase project before creating a Laravel
 * session.
 *
 * Requires the kreait/laravel-firebase package and valid Firebase Admin
 * SDK credentials (FIREBASE_CREDENTIALS in .env).
 */
class FirebaseService
{
    /**
     * Verify a Firebase ID token and return the user's claims.
     *
     * @throws FailedToVerifyToken
     */
    public function verifyIdToken(string $idToken): array
    {
        /** @var \Kreait\Firebase\Auth $auth */
        $auth = app('firebase.auth');

        $verified = $auth->verifyIdToken($idToken);
        $claims = $verified->claims();

        return [
            'uid' => $claims->get('sub'),
            'email' => $claims->get('email'),
            'email_verified' => (bool) $claims->get('email_verified', false),
            'phone' => $claims->get('phone_number'),
            'name' => $claims->get('name'),
            'picture' => $claims->get('picture'),
            // Firebase sign-in provider, e.g. "phone", "password", "google.com"
            'provider' => $this->detectProvider($claims),
        ];
    }

    /**
     * Check whether Firebase Admin SDK is configured.
     */
    public function isConfigured(): bool
    {
        try {
            app('firebase.auth');

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    protected function detectProvider($claims): ?string
    {
        try {
            $identities = $claims->get('firebase')['identities'] ?? [];

            foreach ($identities as $provider => $ids) {
                return $provider; // first provider is the one used to sign in
            }
        } catch (\Throwable) {
            //
        }

        return null;
    }
}
