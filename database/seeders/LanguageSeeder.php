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
                'wishlist' => ['en' => 'Wishlist', 'ur' => 'پسندیدہ فہرست'],
                'added_to_wishlist' => ['en' => 'Added to your wishlist.', 'ur' => 'آپ کی پسندیدہ فہرست میں شامل کر دیا گیا۔'],
                'removed_from_wishlist' => ['en' => 'Removed from your wishlist.', 'ur' => 'پسندیدہ فہرست سے ہٹا دیا گیا۔'],
                'wishlist_empty' => ['en' => 'Your wishlist is empty.', 'ur' => 'آپ کی پسندیدہ فہرست خالی ہے۔'],
                'reviews' => ['en' => 'Reviews', 'ur' => 'تبصرے'],
                'write_review' => ['en' => 'Write a review', 'ur' => 'تبصرہ لکھیں'],
                'your_rating' => ['en' => 'Your rating', 'ur' => 'آپ کی درجہ بندی'],
                'your_review' => ['en' => 'Your review', 'ur' => 'آپ کا تبصرہ'],
                'submit_review' => ['en' => 'Submit review', 'ur' => 'تبصرہ جمع کرائیں'],
                'review_submitted' => ['en' => 'Thank you! Your review has been published.', 'ur' => 'شکریہ! آپ کا تبصرہ شائع کر دیا گیا۔'],
                'review_pending_approval' => ['en' => 'Thank you! Your review will appear after approval.', 'ur' => 'شکریہ! آپ کا تبصرہ منظوری کے بعد نظر آئے گا۔'],
                'no_reviews' => ['en' => 'No reviews yet. Be the first to review!', 'ur' => 'ابھی کوئی تبصرہ نہیں۔ سب سے پہلے تبصرہ کریں!'],
                'based_on' => ['en' => 'based on', 'ur' => 'بنیاد پر'],
                'coupon_code' => ['en' => 'Coupon code', 'ur' => 'کوپن کوڈ'],
                'apply_coupon' => ['en' => 'Apply', 'ur' => 'لاگو کریں'],
                'coupon_applied' => ['en' => 'Coupon applied', 'ur' => 'کوپن لاگو ہو گیا'],
                'coupon_removed' => ['en' => 'Coupon removed.', 'ur' => 'کوپن ہٹا دیا گیا۔'],
                'enter_coupon' => ['en' => 'Enter coupon code', 'ur' => 'کوپن کوڈ درج کریں'],
                'discount' => ['en' => 'Discount', 'ur' => 'رعایت'],
                'tax' => ['en' => 'Tax', 'ur' => 'ٹیکس'],
                'my_account' => ['en' => 'My Account', 'ur' => 'میرا اکاؤنٹ'],
                'account_dashboard' => ['en' => 'Account Dashboard', 'ur' => 'اکاؤنٹ ڈیش بورڈ'],
                'order_history' => ['en' => 'Order History', 'ur' => 'آرڈر کی تاریخ'],
                'address_book' => ['en' => 'Address Book', 'ur' => 'پتوں کی کتاب'],
                'add_address' => ['en' => 'Add Address', 'ur' => 'پتہ شامل کریں'],
                'edit_profile' => ['en' => 'Edit Profile', 'ur' => 'پروفائل میں ترمیم'],
                'default_address' => ['en' => 'Default', 'ur' => 'طے شدہ'],
                'set_default' => ['en' => 'Set as default', 'ur' => 'طے شدہ بنائیں'],
                'no_addresses' => ['en' => 'No saved addresses yet.', 'ur' => 'ابھی کوئی محفوظ پتہ نہیں۔'],
                'label' => ['en' => 'Label', 'ur' => 'لیبل'],
                'postal_code' => ['en' => 'Postal Code', 'ur' => 'ڈاک کوڈ'],
                'select_variant' => ['en' => 'Select option', 'ur' => 'آپشن منتخب کریں'],
                'search_filters' => ['en' => 'Search & Filters', 'ur' => 'تلاش و فلٹر'],
                'min_price' => ['en' => 'Min price', 'ur' => 'کم از کم قیمت'],
                'max_price' => ['en' => 'Max price', 'ur' => 'زیادہ سے زیادہ قیمت'],
                'sort_by' => ['en' => 'Sort by', 'ur' => 'ترتیب'],
                'newest' => ['en' => 'Newest', 'ur' => 'نئی ترین'],
                'price_low_high' => ['en' => 'Price: Low to High', 'ur' => 'قیمت: کم سے زیادہ'],
                'price_high_low' => ['en' => 'Price: High to Low', 'ur' => 'قیمت: زیادہ سے کم'],
                'in_stock_only' => ['en' => 'In stock only', 'ur' => 'صرف دستیاب'],
                'apply_filters' => ['en' => 'Apply filters', 'ur' => 'فلٹر لاگو کریں'],
                'clear_filters' => ['en' => 'Clear', 'ur' => 'صاف کریں'],
                'no_products_found' => ['en' => 'No products match your filters.', 'ur' => 'کوئی مصنوعات آپ کے فلٹر سے مطابقت نہیں رکھتیں۔'],
                'phone_number' => ['en' => 'Phone Number', 'ur' => 'فون نمبر'],
                'phone_hint' => ['en' => 'Include your country code, e.g. +92\u2026', 'ur' => 'اپنا ملکی کوڈ شامل کریں، مثلاً +92\u2026'],
                'send_otp' => ['en' => 'Send OTP', 'ur' => 'OTP بھیجیں'],
                'enter_otp' => ['en' => 'Enter the 6-digit code sent to your phone', 'ur' => 'اپنے فون پر بھیجا گیا 6 ہندسوں کا کوڈ درج کریں'],
                'verify_otp' => ['en' => 'Verify OTP', 'ur' => 'OTP کی تصدیق کریں'],
                'resend_otp' => ['en' => 'Resend code', 'ur' => 'کوڈ دوبارہ بھیجیں'],
                'continue_with_google' => ['en' => 'Continue with Google', 'ur' => 'گوگل سے جاری رکھیں'],
                'create_account' => ['en' => 'Create Account', 'ur' => 'اکاؤنٹ بنائیں'],
                'secure_signin' => ['en' => 'Secure sign-in powered by Firebase', 'ur' => 'Firebase سے محفوظ سائن اِن'],
                'google_hint' => ['en' => 'Sign in with your Google account', 'ur' => 'اپنے گوگل اکاؤنٹ سے سائن اِن کریں'],
                'firebase_note' => ['en' => 'Your credentials are verified securely by Firebase', 'ur' => 'آپ کی معلومات Firebase سے محفوظ طریقے سے تصدیق ہوتی ہیں'],
                'phone_required' => ['en' => 'Please enter your phone number.', 'ur' => 'براہ کرم اپنا فون نمبر درج کریں۔'],
                'otp_required' => ['en' => 'Please enter the OTP code.', 'ur' => 'براہ کرم OTP کوڈ درج کریں۔'],
                'email_pass_required' => ['en' => 'Please enter your email and password.', 'ur' => 'براہ کرم اپنا ای میل اور پاس ورڈ درج کریں۔'],
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
                'coupons' => ['en' => 'Coupons', 'ur' => 'کوپن'],
                'reviews' => ['en' => 'Reviews', 'ur' => 'تبصرے'],
                'pages' => ['en' => 'Pages', 'ur' => 'صفحات'],
                'banners' => ['en' => 'Banners', 'ur' => 'بینرز'],
                'media' => ['en' => 'Media', 'ur' => 'میڈیا'],
                'approve' => ['en' => 'Approve', 'ur' => 'منظور کریں'],
                'unapprove' => ['en' => 'Unapprove', 'ur' => 'نامنظور کریں'],
                'upload' => ['en' => 'Upload', 'ur' => 'اپ لوڈ'],
                'code' => ['en' => 'Code', 'ur' => 'کوڈ'],
                'type' => ['en' => 'Type', 'ur' => 'قسم'],
                'value' => ['en' => 'Value', 'ur' => 'قدر'],
                'percent' => ['en' => 'Percent (%)', 'ur' => 'فیصد (%)'],
                'fixed' => ['en' => 'Fixed amount', 'ur' => 'مقررہ رقم'],
                'min_order' => ['en' => 'Min. order', 'ur' => 'کم از کم آرڈر'],
                'max_uses' => ['en' => 'Max uses', 'ur' => 'زیادہ سے زیادہ استعمال'],
                'used' => ['en' => 'Used', 'ur' => 'استعمال شدہ'],
                'valid_from' => ['en' => 'Valid from', 'ur' => 'سے درست'],
                'valid_until' => ['en' => 'Valid until', 'ur' => 'تک درست'],
                'slug' => ['en' => 'Slug', 'ur' => 'سلگ'],
                'body' => ['en' => 'Body', 'ur' => 'مواد'],
                'link' => ['en' => 'Link', 'ur' => 'لنک'],
                'image' => ['en' => 'Image', 'ur' => 'تصویر'],
                'title' => ['en' => 'Title', 'ur' => 'عنوان'],
                'subtitle' => ['en' => 'Subtitle', 'ur' => 'ذیلی عنوان'],
                'sort_order' => ['en' => 'Sort order', 'ur' => 'ترتیب'],
                'rating' => ['en' => 'Rating', 'ur' => 'درجہ بندی'],
                'comment' => ['en' => 'Comment', 'ur' => 'تبصرہ'],
                'customer' => ['en' => 'Customer', 'ur' => 'گاہک'],
                'product' => ['en' => 'Product', 'ur' => 'مصنوعات'],
                'attach_to_product' => ['en' => 'Attach to product', 'ur' => 'مصنوعات سے منسلک کریں'],
                'variants' => ['en' => 'Variants', 'ur' => 'مختلف اقسام'],
                'add_variant' => ['en' => 'Add variant', 'ur' => 'قسم شامل کریں'],
                'variant_name' => ['en' => 'Variant name (e.g. Size: M / Color: Red)', 'ur' => 'قسم کا نام'],
                'gallery' => ['en' => 'Gallery', 'ur' => 'گیلری'],
                'upload_images' => ['en' => 'Upload images', 'ur' => 'تصاویر اپ لوڈ کریں'],
                'set_primary' => ['en' => 'Primary', 'ur' => 'بنیادی'],
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
