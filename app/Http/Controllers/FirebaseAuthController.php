<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CartController;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Firebase Authentication controller.
 *
 * Flow:
 *   1. showLogin() renders the login page. The Firebase JS SDK handles
 *      Phone OTP, Email+Password and Google Sign-In entirely in the browser.
 *   2. On success the browser POSTs the Firebase ID token to verify().
 *   3. verify() cryptographically validates the token, finds or creates the
 *      local user (linked by firebase_uid, falling back to email), then
 *      starts a normal Laravel session via Auth::login().
 */
class FirebaseAuthController extends Controller
{
    public function showLogin(FirebaseService $firebase)
    {
        return view('auth.firebase-login', [
            'firebaseConfigured' => $firebase->isConfigured()
                && config('firebase.api_key'),
            'mode' => request('mode', 'signin'),
        ]);
    }

    public function verify(Request $request, FirebaseService $firebase)
    {
        $request->validate([
            'id_token' => 'required|string',
        ]);

        try {
            $claims = $firebase->verifyIdToken($request->input('id_token'));
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Invalid or expired Firebase token.',
            ], 401);
        }

        $user = User::where('firebase_uid', $claims['uid'])->first();

        // Link pre-existing local accounts by email (e.g. the seeded admin).
        if (! $user && ! empty($claims['email'])) {
            $user = User::where('email', $claims['email'])->first();

            if ($user) {
                $user->update(['firebase_uid' => $claims['uid']]);
            }
        }

        if (! $user) {
            $user = User::create([
                'name' => $claims['name']
                    ?: ($claims['email'] ?: ($claims['phone'] ?: 'User')),
                'email' => $claims['email'] ?: $claims['uid'].'@firebase.local',
                'phone' => $claims['phone'],
                'avatar' => $claims['picture'],
                'firebase_uid' => $claims['uid'],
                'password' => null,
                'role' => User::ROLE_CUSTOMER,
                'email_verified_at' => $claims['email_verified'] ? now() : null,
            ]);
        } else {
            // Keep profile fields fresh on every login.
            $user->update(array_filter([
                'phone' => $claims['phone'] ?? $user->phone,
                'avatar' => $claims['picture'] ?? $user->avatar,
            ]));
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        // Preserve the guest cart across the login.
        CartController::mergeGuestCart($user->id);

        return response()->json([
            'redirect' => session()->pull('url.intended', route('home')),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // The Firebase JS SDK signs out client-side (see login view).
        return redirect()->route('home');
    }
}
