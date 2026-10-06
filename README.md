# Multi-Ecom

A modular, WordPress-style e-commerce platform built with **Laravel 11**.

## Architecture

```
multi-ecom/
├── app/                  # Core application
│   ├── Http/Controllers/ # Storefront + Admin controllers
│   ├── Models/           # Eloquent models (User, Product, Category, Order, ...)
│   ├── Services/         # ThemeManager, PluginManager, TranslationService
│   └── helpers.php       # trans_db() helper
├── config/
├── database/
│   ├── migrations/       # Core tables (incl. languages, translations)
│   └── seeders/          # Admin user, languages (EN default + UR), demo data
├── plugins/              # WordPress-style plugins
│   ├── MultiVendor/      # Vendor registration, dashboards, commissions
│   ├── PaymentCod/       # Cash on Delivery payment method
│   └── ShippingFlat/     # Flat-rate shipping method
├── resources/views/      # Core Blade views (admin panel + fallback storefront)
├── routes/
└── themes/
    └── default/          # Default storefront theme (theme.json manifest)
```

### Design principles

1. **Core = single-vendor shop.** Products, categories, cart, checkout, orders,
   admin dashboard — everything works out of the box with zero plugins.
2. **Themes** swap the entire storefront look. `ThemeManager` prepends the
   active theme's view path so theme Blade files override core ones.
   Switch themes from Admin → Themes (no code changes needed).
3. **Plugins** extend functionality. `PluginManager` auto-discovers
   `plugins/*/plugin.json`, registers enabled plugins' service providers,
   and exposes a simple hook system (`add_action` / `do_action`).
   Enable the **MultiVendor** plugin to turn the shop into a marketplace.
4. **Multi-language from day 1 (core, not a plugin).** Languages and
   translations live in the database (`languages`, `translations` tables),
   managed entirely from Admin → Languages / Translations. Frontend strings
   use `trans_db('group.key')` with fallback: requested locale → default
   language → lang file → key itself. RTL flag per language.

## Requirements

- PHP ^8.2
- Composer
- MySQL 8+ (or MariaDB 10.6+ / SQLite for local dev)
- Node.js (optional, only if you compile theme assets)

## Installation

```bash
# 1. Clone
git clone https://github.com/npdbilal/multi-ecom.git
cd multi-ecom

# 2. Install PHP dependencies
composer install

# 3. Environment
cp .env.example .env
php artisan key:generate

# 4. Database — edit .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD), then:
php artisan migrate --seed

# 5. Serve
php artisan serve
```

Open http://127.0.0.1:8000

## Firebase Authentication setup

Authentication is handled by **Firebase** (Phone OTP, Email+Password, Google
Sign-In). The browser signs in via the Firebase JS SDK; the ID token is POSTed
to `/auth/firebase/verify`, verified server-side with the Firebase Admin SDK,
and a normal Laravel session is started (`Auth::login`).

```bash
# 1. Create a project at console.firebase.google.com
# 2. Build → Authentication → Sign-in method → enable:
#      Phone, Email/Password, Google
# 3. Project Settings → Your apps → Web app → copy the config values
# 4. Project Settings → Service accounts → Generate new private key
#      → save as storage/firebase-service-account.json
```

```env
FIREBASE_API_KEY=...
FIREBASE_AUTH_DOMAIN=....firebaseapp.com
FIREBASE_PROJECT_ID=...
FIREBASE_STORAGE_BUCKET=....appspot.com
FIREBASE_MESSAGING_SENDER_ID=...
FIREBASE_APP_ID=...
FIREBASE_CREDENTIALS=/absolute/path/to/storage/firebase-service-account.json
```

> For Phone OTP testing, add your number under Authentication → Sign-in
> method → Phone → Phone numbers for testing.

### Default seeded accounts

| Role  | Email             | Password   |
|-------|-------------------|------------|
| Admin | admin@multiecom.test | password |

> The admin account is seeded with a local password, but login goes through
> Firebase. To claim it: in the Firebase Console create an Email/Password user
> `admin@multiecom.test` — on first sign-in the account is linked by email and
> the admin role is preserved. (`app/Http/Controllers/AuthController.php` is
> the legacy Laravel-auth controller and is no longer wired to any route.)

### Seeded languages

- **English** (`en`) — default
- **Urdu** (`ur`) — RTL enabled, basic `shop` + `admin` translations

Add more languages any time from **Admin → Languages**.

## Key routes

| Route | Description |
|---|---|
| `/` | Storefront home |
| `/products`, `/products/{slug}` | Catalog |
| `/cart`, `/checkout` | Cart & checkout |
| `/language/{code}` | Switch frontend language (session) |
| `/admin` | Admin dashboard |
| `/admin/languages`, `/admin/translations` | Language & translation manager |
| `/admin/themes`, `/admin/plugins` | Theme switcher, plugin enable/disable |

## Enabling multi-vendor mode

1. Go to **Admin → Plugins**
2. Enable **MultiVendor**
3. Run `php artisan migrate` (plugin ships its own migrations)
4. Vendors register at `/vendor/register`; admin approves at **Admin → Vendors**
5. Set commission % at **Admin → Vendors → Settings**

## Translation system

```php
// Anywhere in PHP
trans_db('shop.add_to_cart');            // current locale
trans_db('shop.add_to_cart', 'ur');      // explicit locale

// In Blade
{{ trans_db('shop.checkout') }}
```

Fallback chain: `requested locale → default language → lang file → raw key`.

Missing translations are flagged in **Admin → Translations** (keys that exist
in the default language but not in another active language).

## License

MIT
