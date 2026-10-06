<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Translation;
use Illuminate\Database\Seeder;

/**
 * Seeds English (default) + Urdu (RTL) with the base translation catalogue.
 *
 * Groups: shop (storefront), admin (admin panel), auth (login/register).
 * Add new keys here when you add new UI strings — then run
 *   php artisan db:seed --class=LanguageSeeder
 * or add them from Admin → Translations.
 */
class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $english = Language::firstOrCreate(
            ['code' => 'en'],
            [
                'name' => 'English',
                'native_name' => 'English',
                'is_default' => true,
                'is_active' => true,
                'is_rtl' => false,
                'sort_order' => 1,
            ]
        );

        // Ensure exactly one default.
        $english->makeDefault();

        $urdu = Language::firstOrCreate(
            ['code' => 'ur'],
            [
                'name' => 'Urdu',
                'native_name' => 'اردو',
                'is_default' => false,
                'is_active' => true,
                'is_rtl' => true,
                'sort_order' => 2,
            ]
        );

        foreach ($this->catalogue() as $group => $keys) {
            foreach ($keys as $key => $values) {
                foreach ($values as $code => $value) {
                    Translation::updateOrCreate(
                        ['language_code' => $code, 'group' => $group, 'key' => $key],
                        ['value' => $value]
                    );
                }
            }
        }
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     *   [group => [key => ['en' => ..., 'ur' => ...]]]
     */
    protected function catalogue(): array
    {
        return [
            'shop' => [
                'home' => ['en' => 'Home', 'ur' => 'ہوم'],
                'products' => ['en' => 'Products', 'ur' => 'مصنوعات'],
                'categories' => ['en' => 'Categories', 'ur' => 'زمرے'],
                'cart' => ['en' => 'Cart', 'ur' => 'ٹوکری'],
                'checkout' => ['en' => 'Checkout', 'ur' => 'ادائیگی'],
                'orders' => ['en' => 'Orders', 'ur' => 'آرڈرز'],
                'login' => ['en' => 'Login', 'ur' => 'لاگ اِن'],
                'register' => ['en' => 'Register', 'ur' => 'رجسٹر'],
                'logout' => ['en' => 'Logout', 'ur' => 'لاگ آؤٹ'],
                'search' => ['en' => 'Search products...', 'ur' => 'مصنوعات تلاش کریں...'],
                'add_to_cart' => ['en' => 'Add to Cart', 'ur' => 'ٹوکری میں ڈالیں'],
                'added_to_cart' => ['en' => 'Added to cart.', 'ur' => 'ٹوکری میں شامل کر دیا گیا۔'],
                'cart_updated' => ['en' => 'Cart updated.', 'ur' => 'ٹوکری اپ ڈیٹ ہو گئی۔'],
                'removed_from_cart' => ['en' => 'Removed from cart.', 'ur' => 'ٹوکری سے ہٹا دیا گیا۔'],
                'cart_empty' => ['en' => 'Your cart is empty.', 'ur' => 'آپ کی ٹوکری خالی ہے۔'],
                'subtotal' => ['en' => 'Subtotal', 'ur' => 'ذیلی مجموعہ'],
                'shipping' => ['en' => 'Shipping', 'ur' => 'ترسیل'],
                'total' => ['en' => 'Total', 'ur' => 'کل'],
                'place_order' => ['en' => 'Place Order', 'ur' => 'آرڈر کریں'],
                'order_placed' => ['en' => 'Thank you! Your order has been placed.', 'ur' => 'شکریہ! آپ کا آرڈر موصول ہو گیا۔'],
                'order_number' => ['en' => 'Order number', 'ur' => 'آرڈر نمبر'],
                'featured_products' => ['en' => 'Featured Products', 'ur' => 'نمایاں مصنوعات'],
                'latest_products' => ['en' => 'Latest Products', 'ur' => 'نئی مصنوعات'],
                'shop_by_category' => ['en' => 'Shop by Category', 'ur' => 'زمرے کے حساب سے خریدیں'],
                'view_all' => ['en' => 'View all', 'ur' => 'سب دیکھیں'],
                'in_stock' => ['en' => 'In stock', 'ur' => 'دستیاب ہے'],
                'out_of_stock' => ['en' => 'Out of stock', 'ur' => 'ختم ہو گیا'],
                'quantity' => ['en' => 'Quantity', 'ur' => 'مقدار'],
                'description' => ['en' => 'Description', 'ur' => 'تفصیل'],
                'related_products' => ['en' => 'Related Products', 'ur' => 'متعلقہ مصنوعات'],
                'standard_shipping' => ['en' => 'Standard Shipping', 'ur' => 'معیاری ترسیل'],
                'cash_on_delivery' => ['en' => 'Cash on Delivery', 'ur' => 'ڈیلیوری پر ادائیگی'],
                'payment_method' => ['en' => 'Payment Method', 'ur' => 'ادائیگی کا طریقہ'],
                'shipping_method' => ['en' => 'Shipping Method', 'ur' => 'ترسیل کا طریقہ'],
                'customer_details' => ['en' => 'Customer Details', 'ur' => 'گاہک کی تفصیلات'],
                'full_name' => ['en' => 'Full Name', 'ur' => 'پورا نام'],
                'email' => ['en' => 'Email', 'ur' => 'ای میل'],
                'phone' => ['en' => 'Phone', 'ur' => 'فون'],
                'address' => ['en' => 'Address', 'ur' => 'پتہ'],
                'city' => ['en' => 'City', 'ur' => 'شہر'],
                'country' => ['en' => 'Country', 'ur' => 'ملک'],
                'notes' => ['en' => 'Order notes (optional)', 'ur' => 'آرڈر نوٹس (اختیاری)'],
                'language' => ['en' => 'Language', 'ur' => 'زبان'],
                'welcome' => ['en' => 'Welcome to our store', 'ur' => 'ہمارے اسٹور میں خوش آمدید'],
                'hero_title' => ['en' => 'Everything you love, delivered.', 'ur' => 'آپ کی پسندیدہ ہر چیز، آپ کے دروازے پر۔'],
                'hero_subtitle' => ['en' => 'Shop quality products from trusted sellers.', 'ur' => 'قابلِ اعتماد فروخت کنندگان سے معیاری مصنوعات خریدیں۔'],
                'announcement' => ['en' => 'Free shipping on orders over $50', 'ur' => '50 ڈالر سے زائد کے آرڈر پر مفت ترسیل'],
                'flat_rate_shipping' => ['en' => 'Flat Rate Shipping', 'ur' => 'فلیٹ ریٹ ترسیل'],
                'shop_now' => ['en' => 'Shop Now', 'ur' => 'ابھی خریدیں'],
                'price' => ['en' => 'Price', 'ur' => 'قیمت'],
                'sort_by' => ['en' => 'Sort by', 'ur' => 'ترتیب'],
                'newest' => ['en' => 'Newest', 'ur' => 'نئی ترین'],
                'price_low_high' => ['en' => 'Price: Low to High', 'ur' => 'قیمت: کم سے زیادہ'],
                'price_high_low' => ['en' => 'Price: High to Low', 'ur' => 'قیمت: زیادہ سے کم'],
            ],
            'auth' => [
                'invalid_credentials' => ['en' => 'These credentials do not match our records.', 'ur' => 'یہ معلومات ہمارے ریکارڈ سے مطابقت نہیں رکھتیں۔'],
                'login_title' => ['en' => 'Welcome back', 'ur' => 'خوش آمدید'],
                'register_title' => ['en' => 'Create your account', 'ur' => 'اپنا اکاؤنٹ بنائیں'],
                'name' => ['en' => 'Name', 'ur' => 'نام'],
                'password' => ['en' => 'Password', 'ur' => 'پاس ورڈ'],
                'confirm_password' => ['en' => 'Confirm Password', 'ur' => 'پاس ورڈ کی تصدیق'],
                'remember_me' => ['en' => 'Remember me', 'ur' => 'مجھے یاد رکھیں'],
                'no_account' => ['en' => "Don't have an account?", 'ur' => 'اکاؤنٹ نہیں ہے؟'],
                'have_account' => ['en' => 'Already have an account?', 'ur' => 'پہلے سے اکاؤنٹ ہے؟'],
            ],
            'admin' => [
                'dashboard' => ['en' => 'Dashboard', 'ur' => 'ڈیش بورڈ'],
                'products' => ['en' => 'Products', 'ur' => 'مصنوعات'],
                'categories' => ['en' => 'Categories', 'ur' => 'زمرے'],
                'orders' => ['en' => 'Orders', 'ur' => 'آرڈرز'],
                'customers' => ['en' => 'Customers', 'ur' => 'گاہک'],
                'languages' => ['en' => 'Languages', 'ur' => 'زبانیں'],
                'translations' => ['en' => 'Translations', 'ur' => 'تراجم'],
                'themes' => ['en' => 'Themes', 'ur' => 'تھیمز'],
                'plugins' => ['en' => 'Plugins', 'ur' => 'پلگ اِنز'],
                'settings' => ['en' => 'Settings', 'ur' => 'ترتیبات'],
                'revenue' => ['en' => 'Revenue', 'ur' => 'آمدنی'],
                'recent_orders' => ['en' => 'Recent Orders', 'ur' => 'حالیہ آرڈرز'],
                'low_stock' => ['en' => 'Low Stock', 'ur' => 'کم اسٹاک'],
                'add_new' => ['en' => 'Add New', 'ur' => 'نیا شامل کریں'],
                'edit' => ['en' => 'Edit', 'ur' => 'ترمیم'],
                'delete' => ['en' => 'Delete', 'ur' => 'حذف کریں'],
                'save' => ['en' => 'Save', 'ur' => 'محفوظ کریں'],
                'cancel' => ['en' => 'Cancel', 'ur' => 'منسوخ کریں'],
                'actions' => ['en' => 'Actions', 'ur' => 'عمل'],
                'name' => ['en' => 'Name', 'ur' => 'نام'],
                'price' => ['en' => 'Price', 'ur' => 'قیمت'],
                'stock' => ['en' => 'Stock', 'ur' => 'اسٹاک'],
                'status' => ['en' => 'Status', 'ur' => 'حالت'],
                'active' => ['en' => 'Active', 'ur' => 'فعال'],
                'inactive' => ['en' => 'Inactive', 'ur' => 'غیر فعال'],
                'default' => ['en' => 'Default', 'ur' => 'طے شدہ'],
                'make_default' => ['en' => 'Make Default', 'ur' => 'طے شدہ بنائیں'],
                'enable' => ['en' => 'Enable', 'ur' => 'فعال کریں'],
                'disable' => ['en' => 'Disable', 'ur' => 'غیر فعال کریں'],
                'enabled' => ['en' => 'Enabled', 'ur' => 'فعال'],
                'disabled' => ['en' => 'Disabled', 'ur' => 'غیر فعال'],
                'saved' => ['en' => 'Saved successfully.', 'ur' => 'کامیابی سے محفوظ ہو گیا۔'],
                'deleted' => ['en' => 'Deleted successfully.', 'ur' => 'کامیابی سے حذف ہو گیا۔'],
                'key' => ['en' => 'Key', 'ur' => 'کلید'],
                'value' => ['en' => 'Value', 'ur' => 'قدر'],
                'group' => ['en' => 'Group', 'ur' => 'گروپ'],
                'search' => ['en' => 'Search...', 'ur' => 'تلاش کریں...'],
                'missing_translations' => ['en' => 'Missing translations', 'ur' => 'غیر موجود تراجم'],
                'sync_missing' => ['en' => 'Sync missing keys', 'ur' => 'غیر موجود کلیدیں ہم آہنگ کریں'],
                'translations_synced' => ['en' => ':count translation keys synced.', 'ur' => ':count ترجمے کی کلیدیں ہم آہنگ ہو گئیں۔'],
                'activate' => ['en' => 'Activate', 'ur' => 'فعال کریں'],
                'version' => ['en' => 'Version', 'ur' => 'ورژن'],
                'author' => ['en' => 'Author', 'ur' => 'مصنف'],
                'theme_activated' => ['en' => 'Theme activated.', 'ur' => 'تھیم فعال ہو گئی۔'],
                'theme_not_found' => ['en' => 'Theme not found.', 'ur' => 'تھیم نہیں ملی۔'],
                'plugin_enabled' => ['en' => 'Plugin enabled.', 'ur' => 'پلگ اِن فعال ہو گیا۔'],
                'plugin_disabled' => ['en' => 'Plugin disabled.', 'ur' => 'پلگ اِن غیر فعال ہو گیا۔'],
                'plugin_not_found' => ['en' => 'Plugin not found.', 'ur' => 'پلگ اِن نہیں ملا۔'],
                'view_store' => ['en' => 'View Store', 'ur' => 'اسٹور دیکھیں'],
                'code' => ['en' => 'Code', 'ur' => 'کوڈ'],
                'native_name' => ['en' => 'Native Name', 'ur' => 'مقامی نام'],
                'rtl' => ['en' => 'RTL', 'ur' => 'دائیں سے بائیں'],
            ],
        };
    }
}
